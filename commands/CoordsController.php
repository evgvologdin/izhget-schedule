<?php

namespace app\commands;

use Yii;
use app\models\Station;

class CoordsController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $stations = $this->getStationCoords();
        
        foreach ($stations AS $station) {
            $model = Station::find()->where(['igis_id' => $station->id])->one();
            $model = ($model instanceof Station) ? $model : new Station;
            
            $model->setAttributes([
                'izhget_id' => ($model->izhget_id) ? $model->izhget_id : 0,
                'igis_id'   => $station->id,
                'igis_name' => $station->name,
                'lat'       => $station->lat,
                'lon'       => $station->lon,
                'routes'    => $station->routes,
            ]);
            
            if($model->save()) {
                echo sprintf("Save %s\n", $model->id);
            } else {
                var_dump($model->getErrors());
            }
        }
    }
    
    protected function getStationCoords()
    {
        $result = [];
        $regexp = '/setCenter\(\[(?P<lat>\d+\.\d+),\s(?P<lon>\d+\.\d+).*?<b>.*?<\/span>(?P<name>.*?)<\//uis';
        $urls   = $this->getStationsURL();
        
        foreach ($urls AS $index => $value) {
            $info = $this->getHTML($value);
            
            if (!preg_match($regexp, $info, $coords)) {
                continue;
            }
            
            if (!preg_match_all('/nom=(\d+)/uis', $info, $routes)) {
                continue;
            }
            
            $result[] = (object) [
                'id'     => $index,
                'name'   => $coords['name'],
                'lat'    => $coords['lat'],
                'lon'    => $coords['lon'],
                'routes' => $routes[1]
            ];
        }
        
        return $result;
    }
    
    protected function getStationsURL()
    {
        $result = [];
        $trams  = $this->getHTML('http://igis.ru/gortrans/tram');
        
        if (!preg_match_all('#/gortrans/tram/(\d+)#uis', $trams, $trams)) {
            return false;
        }
        
        foreach ($trams[1] AS $id) {
            $stations = $this->getHTML('http://map.igis.ru/page/gortrans.php', [
                'id'  => $id, 
                'rnd' => mt_rand(1000, 9999)
            ]);
            
            if (!preg_match_all('#/page/station\.php\?id=(\d+)#uis', $stations, $stations)) {
                return false;
            }
            
            foreach ($stations[1] AS $station) {
                $result[$station] = sprintf('http://map.igis.ru/page/station.php?id=%s', $station);
            }
        }
        
        return $result;
    }
    
    protected function getHTML($url, Array $params = [])
    {
        $time    = microtime(1);
        $curl    = curl_init();
        $request = sprintf('%s?%s', ltrim($url, '/'), http_build_query($params));

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_URL, $request);

        $response = curl_exec($curl); curl_close($curl);

        echo sprintf("To: %s, time: %s sec.\n", $request, round(microtime(1) - $time, 4));
        
        return $response;
    }
    
    /**
     * @return \app\components\CIzhgetParser
     */
    protected function api()
    {
        return Yii::$app->api;
    }
}