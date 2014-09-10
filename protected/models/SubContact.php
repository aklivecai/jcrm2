<?php
class SubContact extends Contact
{
	//默认继承的搜索条件
	public function defaultScope()
	{
		$arr = parent::defaultScope();
		$sql = strtr('manageid IN (SELECT manageid FROM :tabl WHERE fromid=:fromid AND branch=:branch AND isbranch=0)',
			array(':tabl'=>Manage::$table
				, ':fromid' =>Tak::getFormid()
				, ':branch' =>Tak::getState('branch',-1)
			)
		);
		$condition = array(
			$sql
		);
		if (isset($arr['condition'])) {
			$condition[] = $arr['condition'];
		}
		$arr['condition'] = implode(" AND ",$condition);
		return $arr;
	}	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
