<?php

return [
    'id'             => 'basic',
    'name'           => 'Расписание трамваев в Ижевске',
    'basePath'       => dirname(__DIR__),
    'defaultRoute'   => 'site/index',
    'bootstrap'      => ['log'],
    'aliases' => [
        '@bower' => dirname(__DIR__) . '/web/client/src/vendor/bower'
    ],
    'components' => array_merge([

    ], require __DIR__ . '/common/components.php'),
    
    'params' => array_merge([
        
    ], require __DIR__ . '/common/params.php'),
];
