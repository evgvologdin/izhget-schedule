<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\HttpException;

/**
 * Class ApiController
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-29
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class ApiController extends \app\controllers\common\Controller
{
    public function beforeAction($action)
    {
        if(parent::beforeAction($action) === false) {
            return false;
        }
        
        //usleep(mt_rand(1000000, 9000000));
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }
    
    public function actionStations()
    {
        return $this->api()->getStations();
    }
    
    public function actionTransferStations()
    {
        return $this->api()->getTransferStations();
    }
    
    public function actionStationName()
    {
        return $this->api()->getStationName(Yii::$app->request->post('id', null));
    }
    
    public function actionRoutes()
    {
        return $this->api()->getRoutes(
            Yii::$app->request->post('from', null),
            Yii::$app->request->post('to',   null),
            new \DateTime(Yii::$app->request->post('date', null))
        );
    }
    
    public function actionTransferRoutes()
    {
        return $this->api()->getTransferRoutes(
            Yii::$app->request->post('from', null),
            Yii::$app->request->post('to',   null),
            Yii::$app->request->post('transfer',   null),
            new \DateTime(Yii::$app->request->post('date', null))
        );
    }
}
