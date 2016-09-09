<?php

namespace app\assets\bower;

use yii\web\AssetBundle;

/**
 * Class RequireAsset
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-31
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class RequireAsset extends AssetBundle
{
    public $js = [
        '/client/src/vendor/bower/requirejs/require.js'
    ];
    
    public $jsOptions = [
        'position'  => \yii\web\View::POS_HEAD,
        'data-main' => 'client/dist/js/app'
    ];
}
