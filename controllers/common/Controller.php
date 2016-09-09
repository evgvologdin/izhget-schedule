<?php

namespace app\controllers\common;

use Yii;

/**
 * Class Controller
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-21
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class Controller extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    
    /**
     * @return \app\components\IIzhgetParser
     */
    protected function api()
    {
        return Yii::$app->api;
    }
}
