<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class DevAssets
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-23
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class DevAssets extends AssetBundle
{    
    public $css = [
        '/client/dist/css/app.css'
    ];
    
    public $depends = [
        'app\assets\IE8FixAssets',
        'app\assets\IE9FixAssets',
        'app\assets\bower\RequireAsset'
    ];
}
