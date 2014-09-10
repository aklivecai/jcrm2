<?php
// <!-- #!/usr/bin/php -->
// change the following paths if necessary
// E_ALL | E_STRICT
// error_reporting(E_ALL);
$yii=dirname(__FILE__).'/../Yii/framework/yii.php';

// $yii=dirname(__FILE__).'/../yii/framework/yiilite.php';
$config=dirname(__FILE__).'/protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);
Yii::createWebApplication($config)->run();
// 防止加密后，别人引用输出 全部变量
exit;
