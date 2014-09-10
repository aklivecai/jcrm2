<?php
return  array(  
	'debug' => false,  
	        // 'install' => true,  
	'enableBizRuleData' => true,  
	'userClass' => 'Manage',
               'superuserName'=>'Admin', // Name of the role with super user privileges. 
               'authenticatedName'=>'Authenticated',  // Name of the authenticated user role. 
               'userIdColumn'=>'manageid', // Name of the user id column in the database. 
               'userNameColumn'=>'user_name',  // Name of the user name column in the database. 
               'enableBizRule'=>true,  // Whether to enable authorization item business rules. 
               'enableBizRuleData'=>true,   // Whether to enable data for business rules. 
               'displayDescription'=>true,  // Whether to use item description instead of name. 
               'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages. 
               'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages. 
 
               'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested. 
               'layout'=>'//layouts/mainRight',  // Layout to use for displaying Rights. 
               'appLayout'=>'//layouts/main', // Application layout. 
               // 'cssFile'=>'rights.css', // Style sheet file to use for Rights. 
);