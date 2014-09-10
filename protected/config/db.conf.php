<?php

$db = array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/db.db3',
            // 表前缀
            'tablePrefix'=>'Tak_',
            'password' => 'aklivecai',
        );
        // uncomment the following to use a MySQL database
$db = array(
            'connectionString' => 'mysql:host=192.168.0.222;dbname=_b2b_ak',
            // 'connectionString' => 'mysql:host=112.124.63.178;dbname=_b2b_ak',
            'emulatePrepare' => true,
            'username' => 'ak',
            'password' => 'aklivecai',
            'charset' => 'utf8',
            'tablePrefix'=>'tak_'
);
// $db['connectionString'] = 'mysql:host=127.0.0.1;dbname=_b2b_ak';
// $db['username'] = 'root';
// $db['password'] ='5201314';

if(YII_DEBUG)
{
    $db_debug = array(
        'enableProfiling' => true,
        'enableParamLogging' => true,
    );

    $db = array_merge($db, $db_debug);
}

return $db;