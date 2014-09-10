<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */
// change the following paths if necessary
//
//

function forLinux() {
    $arr = array();
    @exec(" ifconfig -a ", $arr);
    return $arr;
}

$yii = dirname(__FILE__) . '/../Yii/framework/yii.php';

defined('CIM_DEBUG') or define('YII_DEBUG', true);

$config = dirname(__FILE__) . '/protected/config/test.php';
// remove the following line when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once ($yii);
// Yii::createWebApplication($config)->run();

Yii::createWebApplication($config);

AdminLog::$isLog = false;
if (Tak::isGuest()) {
    $m = 'LoginForm';
    $model = new $m();
    $model->attributes = array(
        'username' => 'admin',
        'password' => 'aklivecai',
        'rememberMe' => 'aklivecai',
        'fromid' => '1',
    );
    $model->login();
}
/*
$m = new FlowStep('create');
$m->attributes = array(
    'step_user' => 0,
    'step_name' => '申请',
    'step_no' => 1,
    'flow_id' => 12,
);
$m->validate();
Tak::KD($m->getErrors());
*/