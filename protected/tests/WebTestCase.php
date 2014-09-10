<?php
define('TEST_BASE_URL','http://localhost/GitHub/CRM/index-test.php/');

class WebTestCase extends CWebTestCase
{
	protected function setUp(){
		parent::setUp();
		$this->setBrowser('*firefox');
		$this->setBrowserUrl(TEST_BASE_URL);
	}
}

