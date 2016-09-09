<?php

if ($_SERVER['SERVER_NAME'] == 'rasp.local') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    $config = require __DIR__ . '/../config/dev.php';
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    $config = require __DIR__ . '/../config/web.php';
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

(new yii\web\Application($config))->run();
