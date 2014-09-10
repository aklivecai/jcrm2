<?php
class ManageDbTest extends CDbTestCase {
    public $fixtures = array(
        'manages' => 'Mange', //可以直接在这里$this->projects引用里面的数据
    );

    public function testCreate()
    {
        $newMode = new Manage;
        $data = array(
        	'fromid' => 1,
        	'manageid' => 1,
        	'user_name' => 'admin',
        	'user_pass' => 'c0b266594634df51f07796e7ca31107e',
        	'salt' => 'gXmz',
        	'user_nicename' => 'Tak',
        	'user_email' => 'test@9juren.com',
        	'isbranch' => '1',
        	'branch' => '404',
        	'add_time' => now(),

        	'user_status' => 1,
        );

        $model->setAttributes($data);
        $this->assertTrue($model->save(false));

    }

}
