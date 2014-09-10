<?php

$db = array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/db.db3',
            // 表前缀
            'tablePrefix'=>'Tak_',
        );
        // uncomment the following to use a MySQL database
$db = array(
            'class'=>'CDbConnection',
            'connectionString' => 'mysql:host=192.168.0.222;dbname=akb2b',
            'emulatePrepare' => true,
            'username' => 'ak',
            'password' => 'aklivecai',
            'charset' => 'utf8',
            'tablePrefix'=>'destoon_'
);
if(YII_DEBUG)
{
    $db_debug = array(
        'enableProfiling' => true,
        'enableParamLogging' => true,
    );
    $db = array_merge($db, $db_debug);
}

return $db;