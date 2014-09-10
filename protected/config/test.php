<?php
$data = CMap::mergeArray(require (dirname(__FILE__) . '/main.php') , array(
    'components' => array(
        'fixture' => array(
            'class' => 'system.test.CDbFixtureManager',
        ) ,
        /* uncomment the following to provide test database connection
        'db'=>array(
        'connectionString'=>'DSN for test database',
        ),
        
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../../../data/jrm-test.db',
        ) ,
        */
    ) ,
));
return $data;
