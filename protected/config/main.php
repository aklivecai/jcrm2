<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$tshiPatch = dirname(__FILE__) ;


Yii::setPathOfAlias('bootstrap', $tshiPatch.'/../extensions/bootstrap');

Yii::setPathOfAlias('wf', $tshiPatch.'/../modules/workflow');

return array(
	'basePath'=>$tshiPatch.DIRECTORY_SEPARATOR.'..',
	// 语言包
	'language'=>'zh_cn', 
	'name'=>'具人同行在线营销管理系统',
	 'timeZone' => 'Asia/Shanghai',
   	 'defaultController'=>'site',

	'theme' => 'crm2013',
	// preloading 'log' component
	'preload'=>array('log'),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=> include( $tshiPatch.'/params.conf.php' ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		// 调试的拓展
		'application.extensions.debugtoolbar.*',
		'application.extensions.jsonDataprovider.*',
		/* for ext yii-rights */
 		'application.modules.rights.*',
    		'application.modules.rights.models.*',  
    		'application.modules.rights.components.*', // Correct paths if necessary.  
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			// 以及默认的位置
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults tan(arg)o localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			 // 'bootstrap.gii',
			// Gii 在GiiModule::generatorPaths 属性指定的目录中查找可用的生成器。 当需要定制时，我们可以在应用程序的配置文件中做如下配置，
			 'generatorPaths'=>array(
			 	'application.gii',   // a path alias
                		'bootstrap.gii',
            	      ),
		),
		'juren',
		'workflow',
	    	'rights' => include( $tshiPatch.'/rights.conf.php' )
	),
	// application components components.authManager.assignmentTable
	'components'=>array(
          'authManager'=> include( $tshiPatch.'/auth.conf.php' ),
	    'mobileDetect' => array(
	        'class' => 'ext.MobileDetect.MobileDetect'
	    ),        
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),        
	    'user' => array(  
	        'class' => 'RWebUser',  
	        'allowAutoLogin'=>true,
            	'loginUrl' => array('/site/login'),
	    ),  
	    'urlManager' => include( $tshiPatch.'/url.conf.php' ),
		'db' => include( $tshiPatch.'/db.conf.php' ),
		'db2' => include( $tshiPatch.'/db2.conf.php' ),
		'errorHandler'=>array(
			// 程序有错的时候跳到指定的action
			'errorAction'=>'site/error',
		),
		'mailer' => array(
		      	'class' => 'application.extensions.mailer.EMailer',
		      	'pathViews' => 'application.views.email',
	      		'pathLayouts' => 'application.views.email.layouts'
   		),

		'log' => array(
            		'class'=>'CLogRouter',
            		'routes'=>include( $tshiPatch.'/log.conf.php'),
       	 ),	
	       'session' => array(
       		 'timeout' => 86400,
       		     // 'autoStart' => true,
			    // 'sessionName' => 'Site Access',
			    // 'cookieMode' => 'only',
			    // 'savePath' => $tshiPatch.'/../../assets/8&ETonz^jk@H',
			    'savePath' => '/i/apache2',
   		 ),
	    'request'=>array(  
	        // Enable Yii Validate CSRF Token  
	        // 'enableCsrfValidation' => true,  
	        // 
	    ),
		'cache'	=> array(
			'class' => 'system.caching.CFileCache',
			'directoryLevel' => 2,
		)  ,

	),

);