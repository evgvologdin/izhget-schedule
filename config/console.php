<?php

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    
    'components' => [
        'api' => [
            'class' => 'app\components\CIzhgetParser'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require_once 'common/db.php',
    ],
    
    'params' => array_merge([
        
    ], require __DIR__ . '/common/params.php'),
];
