<?php

namespace app\controllers;

use Yii;
use app\models\Shedule;
use yii\web\HttpException;

/**
 * Class SiteController
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-21
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class SiteController extends \app\controllers\common\Controller
{
    public function actionIndex()
    {
        $routes = false;
        $model  = new Shedule();
        
        try {
            if(Yii::$app->request->post('Shedule')) {
                $model->load(Yii::$app->request->post());
                $routes = $model->getRoutes();
            }
            
            if(Yii::$app->request->get('Shedule')) {
                $model->load(Yii::$app->request->get());
                $routes = $model->getRoutes();
            }
        } catch(\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }

        
        return $this->render('index', [
            'model'  => $model,
            'routes' => $routes
        ]);
    }
    
    public function actionError()
    {
        
    }
}
