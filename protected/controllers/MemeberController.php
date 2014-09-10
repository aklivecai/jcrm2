<?php

class MemeberController extends Controller
{

	public function init()  
	{     
    		parent::init();
    		$this->primaryName = 'itemid';
    		$this->modelName = 'TestMemeber';
	}

	protected function getSelectOption($q,$not = false) 
	{
		$result = parent::getSelectOption($q);
		$linkName = 'company';
		$result['data']['attributes'][] = 'company';
		if ($q) {
			$result['data']['criteria']->addSearchCondition($linkName,$q,true,'OR');
		}
		return $result;
	}
}
