<?php
class SubManage extends Manage
{

	//默认继承的搜索条件
	public function defaultScope()
	{
		$arr = parent::defaultScope();
		$sql = Subordinate::getSubManageSql();
		$condition = array(
			'branch='.Tak::getState('branch',-1),
		);
		if (isset($arr['condition'])) {
			$condition[] = $arr['condition'];
		}
		$arr['condition'] = implode(" AND ",$condition);
		return $arr;
	}	
}
