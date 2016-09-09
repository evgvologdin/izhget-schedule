<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class IE8FixAssets
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-29
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class IE8FixAssets extends AssetBundle
{
    public $js = [
        'http://html5shiv.googlecode.com/svn/trunk/html5.js',
    ];
    
    public $jsOptions = [
        'condition' => 'lte IE 8',
        'position'  => \yii\web\View::POS_HEAD
    ];
}
