<?php
Yii::import('application.controllers.ManageController');
class ManageTest extends CTestCase
{
	public function testCh()
	{
		$message = new ManageController('messageTest');
       	$this->assertEquals($message->getBranch("Any One Out There?"), "用户");
	}
}