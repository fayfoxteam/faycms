<?php
return array(
    array(
        //PHP错误日志
        'class'=>'fay\log\FileTarget',
        'levels'=>array('error', 'warning'),
        'categories'=>array('php_error'),
        'options'=>array(
            'logFile'=>APPLICATION_PATH . 'runtimes/logs/error.' . date('Y-m-d') . '.log'
        )
    ),
    array(
        //系统自定义错误日志
        'class'=>'fay\log\FileTarget',
        'levels'=>array('error', 'warning'),
        'categories'=>array('app_error'),
        'options'=>array(
            'logFile'=>APPLICATION_PATH . 'runtimes/logs/app.error.' . date('Y-m-d') . '.log'
        )
    ),
    array(
        //系统自定义错误日志
        'class'=>'fay\log\FileTarget',
        'levels'=>array('info'),
        'categories'=>array('app'),
        'options'=>array(
            'logFile'=>APPLICATION_PATH . 'runtimes/logs/app.info.' . date('Y-m-d') . '.log'
        )
    ),
    array(
        //单独用于记录fay\core\HttpException错误码404的异常
        //一般来说有404错误是正常的
        'class'=>'fay\log\FileTarget',
        'levels'=>array('error', 'warning'),
        'categories'=>array('app_access'),
        'options'=>array(
            'logFile'=>APPLICATION_PATH . 'runtimes/logs/app.access.' . date('Y-m-d') . '.log'
        )
    )
);