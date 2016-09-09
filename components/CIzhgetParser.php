<?php

namespace app\components;

use Yii;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use app\components\IIzhgetParser;
use app\models\Station;

/**
 * Class CIzhgetParser
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-21
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class CIzhgetParser extends \yii\base\Component implements IIzhgetParser
{    
    /**
     * Сервер
     * @var string
     */
    public $servers = [
        [
            'address'  => 'http://xn--c1aff6b0c.xn--p1ai/rasp/',
            'encoding' => 'utf-8'
        ],
        [
            'address'  => 'http://izhget.ru/rasp/',
            'encoding' => 'windows-1251'   
        ],
    ];
    
    /**
     * Кодировка на сервере
     * @var string
     */
    public $encoding = 'utf-8';
    
    /**
     * Время кеширования результатов в секундах
     * @var integer
     */
    public $cache_time = 1800;
    
    /**
     * Количество минут на которые будут получены рейсы
     * @var integer
     */
    public $time_limit = 120;
    
    /**
     * Максимальное количество маршрутов для отображения
     * @var integer
     */
    public $routes_limit = 5;
    
    /**
     * Количество минут на которые будут получены рейсы для пересадки
     * @var integer
     */
    public $time_branch_limit = 240;
    
    /**
     * Время необходимое на пересадку в секундах
     * @var integer
     */
    public $transfer_time_wite = 120;
    
    /**
     * Остановки на которых возможны пересадки
     * @var Array
     */
    public $transfer_stations = [66, 17, 4, 36, 65, 30, 39];
    
    public function getStations()
    {
        return $this->getStationsFromCache();
    }
    
    public function getStationName($id)
    {
        $stops = $this->getStations();
        
        if (is_array($stops)) {
            foreach ($stops AS $stop) {
                if($stop['id'] == $id) {
                    return $stop['name'];
                }
            } 
        }
        
        return false;
    }
    
    public function getStationCoords($id)
    {
        $coords = [];
        $model  = Station::find()->where(['izhget_id' => $id])->all();
        
        if (is_array($model) === false || count($model) === 0) {
            return $coords;
        }
        
        foreach ($model AS $coord) {
            $coords[] = [$coord->lat, $coord->lon];
        }
        
        return $coords;
    }
    
    public function getTransferStations()
    {
        $result = [];
        
        foreach ($this->transfer_stations AS $station) {
            array_push($result, [
                'id'   => $station,
                'name' => $this->getStationName($station)
            ]);
        }
        
        ArrayHelper::multisort($result, 'name');
        
        return $result;
    }
    
    protected function getStationsFromCache()
    {
        $cachekey = 'izhget_stations';
        
        if(Yii::$app->cache->exists($cachekey)) {
            return Yii::$app->cache->get($cachekey);
        }
        
        $result = $this->getStationsFromServer();
        Yii::$app->cache->set($cachekey, $result, $this->cache_time);
        
        return $result;
    }
    
    protected function getStationsFromServer()
    {
        $result  = [];
        $pattern = '#option.*?value=[\'"]?(?<id>\d+)[\'"]?.*?>(?<name>.*?)<\/option#uis';
        $source  = $this->getHTML('list_station.php', ['order' => 1, 'route' => 0]);
        if($source === false) {
            return false;
        }
        
        if(preg_match_all($pattern, $source, $data)) {
            foreach($data['id'] AS $index => $value) {
                $value = (integer) $value;
                if($value === 0) {
                    continue;;
                }
                
                array_push($result, [
                    'id'     => $value,
                    'name'   => $this->getNormalizeName($data['name'][$index]),
                    'coords' => $this->getStationCoords($value)
                ]);
            }
            
            ArrayHelper::multisort($result, 'name');
            return $result;
        }
        
        return false;
    }
    
    public function getRoutes($from, $to, \DateTime $date)
    {
        $routes = $this->getRoutesFromServer($from, $to, $date, $this->time_limit);
        $routes = (is_array($routes)) ? array_slice($routes, 0, $this->routes_limit) : $routes;
        $routes = ($routes === false) ? false : $this->getRoutesSummaryTime($routes);
        
        return [
            'name'   => $this->getRouteName($from, $to),
            'routes' => $routes
        ];
    }
    
    public function getRouteName($from, $to, $branch = false)
    {
        if($branch) {
            return sprintf(
                '%s — %s — %s', 
                $this->getStationName($from), 
                $this->getStationName($branch), 
                $this->getStationName($to)
            );
        } else {
            return sprintf(
                '%s — %s', 
                $this->getStationName($from), 
                $this->getStationName($to)
            );
        }
    }
    
    public function getTransferRoutes($from, $to, $branch, \DateTime $date)
    {
        $routes = $this->getTransferRoutesFromServer($from, $to, $branch, $date);
        $routes = (is_array($routes)) ? array_slice($routes, 0, $this->routes_limit) : $routes;
        $routes = ($routes === false) ? false : $this->getRoutesSummaryTime($routes);
        
        return [
            'name'   => $this->getRouteName($from, $to, $branch),
            'routes' => $routes
        ];
    }
    
    protected function getTransferRoutesFromServer($from, $to, $branch, \DateTime $date)
    {
        $routes    = $this->getRoutesFromCache($from, $branch, $date, $this->time_limit);
        $transfers = $this->getRoutesFromCache($branch, $to, $date, $this->time_branch_limit);
        
        if(is_array($routes) === false || is_array($transfers) === false) {
            return false;
        }
        
        foreach ($routes AS $index => &$route) {
            $etime = strtotime($route['to_time']) + $this->transfer_time_wite;
            
            foreach($transfers AS $transfer) {
                $stime = strtotime($transfer['from_time']);
                if($etime <= $stime) {
                    $route = array_merge($route, [
                        'transfer' => [
                            'wite_time' => $this->getOffsetTime($transfer['from_time'], $route['to_time']),
                            'route'     => $transfer
                        ]
                    ]);
                    break;
                }
            }
            
            if(isset($route['transfer']) === false || $route['number'] == $route['transfer']['route']['number']) {
                unset($routes[$index]);
            }
            unset($route);
        }
        
        return (count($routes) > 0) ? $routes : false;
    }
    
    protected function getRoutesFromCache($from, $to, \DateTime $date, $limit)
    {
        $cachekey = 'izhget_routes' . $from . $to . $date->format('Ymdhi') . $limit;
        
        if(Yii::$app->cache->exists($cachekey)) {
            return Yii::$app->cache->get($cachekey);
        }
        
        $result = $this->getRoutesFromServer($from, $to, $date, $limit);
        Yii::$app->cache->set($cachekey, $result, $this->cache_time);
        
        return $result;
    }
    
    protected function getRoutesFromServer($from, $to, \DateTime $date, $limit)
    {
        $result  = [];
        $pattern = '#td.*?>(?<number>\d+)<\/td.*?>(?<stime>\d{2}:\d{2})<\/td.*?>(?<etime>\d{2}:\d{2})#uis';
        $source  = $this->getHTML('load_station.php', [
            'stn'    => $from, 
            'dstn'    => $to, 
            'timeint' => $limit,
            'dt'     => $date->format('d.m.Y'),
            'th_rasp'    => $date->format('H'),
            'tm_rasp'    => $date->format('i')
        ]);
        
        if(preg_match_all($pattern, $source, $data)) {
            foreach($data['number'] AS $index => $value) {
                array_push($result, [
                    'number'      => (integer) $value,
                    'from_time'   => $data['stime'][$index],
                    'to_time'     => $data['etime'][$index],
                    'offset_time' => $this->getOffsetTime($data['etime'][$index], $data['stime'][$index])   
                ]);
            }

            return (count($result) > 0) ? $result : false;
        }
        
        return false;
    }
    
    protected function getNormalizeName($name)
    {
        $name = trim($name);
        
        $name = preg_replace('/^Ул.\s*/uis', 'ул. ', $name);
        $name = preg_replace('/^(Переулок|Переул.|Пер.)\s*/uis', 'пер. ', $name);
        $name = preg_replace('/^М-н.\s*/uis', 'м-н ', $name);
        $name = preg_replace('/^К-р.\s*/uis', 'к-р ', $name);
        $name = preg_replace('/^Проспект\s*/uis', 'пр-т ', $name);
        $name = preg_replace('/^(Речка|Река)\s*/uis', 'р. ', $name);
        $name = preg_replace('/\.\s+/uis', '. ', $name);
        
        return $name;
    }
    
    protected function getOffsetTime($max, $min)
    {
        $min = (integer) ($min instanceof \DateTime) 
            ? strtotime($min->format('H:i')) 
            : strtotime($min);
        
        $max = (integer) ($max instanceof \DateTime) 
            ? strtotime($max->format('H:i')) 
            : strtotime($max);
        
        return $max - $min;
    }
    
    protected function getRoutesSummaryTime(Array $routes)
    {
        foreach ($routes AS &$route) {
            $time = $route['offset_time'];
            
            if(isset($route['transfer'])) {
                $time += $route['transfer']['wite_time'];
                $time += $route['transfer']['route']['offset_time'];
            }
            
            $route = array_merge($route, [
                'summary_time' => gmdate('H:i', $time)
            ]);
            unset($route);
        }
        
        return $routes;
    }
    
    protected function getHTML($uri, Array $params = [])
    {
        
        $start = microtime(1);
        
        foreach($this->servers as $server) {
            $curl    = curl_init();
            $request = sprintf('%s/%s', rtrim($server['address'], '/'), ltrim($uri, '/'));

            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_URL, $request);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    
            $response = curl_exec($curl); curl_close($curl);
            
            if (preg_match('#charset\=([A-z0-9\-\_\s]+\w)#is', $response, $charset)) {
                $charset = $charset[1];
            } else {
                $charset = $server['encoding'];
            }
            
            $response = (strtolower($this->encoding) != strtolower($charset)) 
                ? mb_convert_encoding($response, $this->encoding, $charset)
                : $response;
            
            if(preg_match('#HTTP\/1\.\d\s+200#uis', $response)) {
                Yii::info(sprintf('Request: %s, time: %ssec' . PHP_EOL, $request, microtime(1) - $start), 'izhget');
                return $response;
            } else {
                Yii::error(sprintf('Request: %s, time: %ssec, response: %s', $request, microtime(1) - $start, $response), 'izhget');
            }
        }

        throw new HttpException(static::ERR_SERVER);
    }
}