<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class Shedule
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-25
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class Shedule extends Model
{
    /**
     * Откуда
     * @var integer
     */
    public $from;
    
    /**
     * Куда
     * @var integer
     */
    public $to;
    
    /**
     * Указать дату
     * @var integer
     */
    public $has_date;
    
    /**
     * Текущее время у пользователя
     * @var string
     */
    public $current_time;
    
    /**
     * Указанное время
     * @var string
     */
    public $selected_time;
    
    /**
     * Пересадки
     * @var string
     */
    public $transfer_stations = [];
    
    public function rules()
    {
        return [
            [
                ['from', 'to', 'current_time'],
                'required',
                'message' => ''
            ],
            [
                ['to'],
                'compare',
                'compareAttribute' => 'from',
                'operator' => '!=',
                'message'  => ''
            ],
            [
                ['has_date'],
                'in',
                'range' => ['0', '1']
            ],
            [
                ['transfer_stations'],
                'safe'
            ]
        ];
    }
    
    /**
     * Остановки
     * @return array
     */
    public function getStations()
    {
        return $this->api()->getStations();
    }
    
    /**
     * Остановки на которых возможны пересадки
     * @return array
     */
    public function getTransferStations()
    {
        return $this->api()->getTransferStations();
    }
    
    /**
     * Маршруты
     * @return array
     */
    public function getRoutes()
    {
        $routes = [];
        
        if($this->validate() === false) {
            return false;
        }
        
        $routes[] = $this->api()->getRoutes($this->from, $this->to, $this->date);
        
        if(is_array($this->transfer_stations) && count($this->transfer_stations) > 0) {
            foreach($this->transfer_stations AS $branch) {
                $routes[] = $this->api()->getTransferRoutes($this->from, $this->to, $branch, $this->date);
            }
        }
        
        return $routes;
    }
    
    /**
     *  Дата
     * @return \DateTime
     */
    public function getDate()
    {
        return ($this->has_date == 1) ? new \DateTime($this->selected_time) : new \DateTime($this->current_time);
    }
    
    public function attributeLabels()
    {
        return [
            'from'              => 'Откуда',
            'to'                => 'Куда',
            'has_date'          => 'Указать дату и время',
            'current_time'      => 'Дата и время',
            'selected_time'     => 'Дата и время',
            'transfer_stations' => 'Пересадка'
        ];
    }
    
    /**
     * @return \app\components\IIzhgetParser
     */
    protected function api()
    {
        return Yii::$app->api;
    }
}
