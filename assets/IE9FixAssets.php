<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class IE9FixAssets
 *
 * @author Evgeniy Vologdin <muns@muns.su>
 * @since 2015-08-29
 * @version 0.1
 * @copyright © Evgeniy Vologdin, 2015
 */
class IE9FixAssets extends AssetBundle
{
    public $js = [
    ];
    
    public $jsOptions = [
        'condition' => 'lte IE 9',
    ];
}
