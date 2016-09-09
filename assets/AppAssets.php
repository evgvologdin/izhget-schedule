<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class AppAssets
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-23
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class AppAssets extends AssetBundle
{    
    public $js = [
        '/client/dist/js/app.min.js',
        '//yastatic.net/share/share.js'
    ];
    
    public $css = [
        '/client/dist/css/app.min.css'
    ];
    
    public $depends = [
        'app\assets\IE8FixAssets',
        'app\assets\IE9FixAssets',
    ];
}
