<?php
$log = array(
    array(
        'class' => 'CFileLogRoute', //这表示把日志输出到文件中，下方有详细的
        'levels' => 'error, warning,trace',
        'filter' => 'CLogFilter',
    ) ,
    /*
        array(
            'class'=>'CWebLogRoute',//这表示把日志显示在网页下方，下方有详细的
            'levels'=>'trace, info, error, warning',
            'categories'=>'cool.*,system.db.*',
        ),
    */
    // array(
    //     'class'=>'CEmailLogRoute',
    //     'levels'=>'error, warning',
    //     'emails'=>'aklivecai@gmail.com',
    //     'sentFrom'=>'aklivecai@gmail.com',
    //     'subject'=>'aklivecai@gmail.com',
    // ),

);
if (YII_DEBUG || true) {
    $log_debug = array(
        'class' => 'XWebDebugRouter',
        'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
        'levels' => 'error, warning, trace, profile, info',
        'levels' => 'error, warning, trace, profile, info',
        'levels' => 'error',
        'categories' => 'cool.*,system.db.CDbCommand,php',
        'allowedIPs' => array(
            // '127.0.0.1',
             // '192.168.0.201',
             // '192.168.0.201',
        ) ,
    );
    $log[] = $log_debug;
}
return $log;
/*
'filter'=>'CLogFilter',
*/
