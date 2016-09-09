<?php

return [
    'api' => [
        'class' => 'app\components\CIzhgetParser'
    ],
    'request' => [
        'cookieValidationKey' => 'Lm1n6tN1Ed3Zl9dGBSJvPZd9kRajnOtx',
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'urlManager' => [
        'class' => 'yii\web\UrlManager',
        'enablePrettyUrl' => true,
        'showScriptName'  => false,
        'rules' => [
            '/'               => 'site/index',
            '<c:\w+>'         => '<c>/index',
            '<c:\w+>/<a:\w+>' => '<c>/<a>',
        ]
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class'   => 'yii\log\FileTarget',
                'levels'  => ['error', 'warning'],
                'logVars' => [],
                'logFile' => '@app/runtime/logs/app.log',
            ],
            [
                'class'      => 'yii\log\FileTarget',
                'categories' => ['izhget'],
                'levels'     => ['info', 'error', 'warning'],
                'logVars'    => [],
                'logFile'    => '@app/runtime/logs/api.log',
            ],
            [
                'class'      => 'yii\log\EmailTarget',
                'categories' => ['izhget'],
                'levels'     => ['error', 'warning'],
                'logVars'    => ['_GET', '_POST'],
                'message'    => [
                    'from'    => 'muns@muns.su',
                    'to'      => 'muns@muns.su',
                    'subject' => 'RASP.MUNS.SU ERROR',
                ],
            ]
        ],
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'db' => require_once 'db.php',
];
