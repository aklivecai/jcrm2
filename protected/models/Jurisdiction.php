<?php 
class Jurisdiction 
{
	// 类型标识(0::Operation=操作,1::Task=任务,2::Role=角色)
	public static
		$types = array(
			'0' =>'操作',
			'1' =>'任务',
			'2' =>'部门',
		);
	private static  $_authorizer = null;

	public static function getAuthorizer()
	{
		if( isset(self::$_authorizer)===false ){
			self::$_authorizer = Rights::getAuthorizer();
		}
		return self::$_authorizer;
	}

	public static function getTypeName($type)
	{
		$result = isset(self::$types[$type])?self::$types[$type]:'';
		return $result;
	}

	public static function getJurisdiction($id)
	{
		$sql = ' SELECT name,t1.type,description,t1.bizrule,t1.data,weight 
			FROM  {{rbac_authitem}} t1 
			LEFT JOIN {{rbac_authassignment}} t2 ON name=t2.itemname 
			LEFT JOIN {{rbac_rights}} t3 ON name=t3.itemname 
			WHERE 
				userid=:userid   
			ORDER 
				BY t1.type DESC, weight ASC
			';
		$data =  Tak::getDb('db')->createCommand($sql)->queryAll(TRUE,array(':userid'=>$id));
		$result = array();
		foreach ($data as $key => $value) {
			$id = Tak::setCryptKey($value['name']);
			$result[$value['name']] = array(
					'id'=> $id,
					'name'=> $value['name'],
					'title'=>$value['description'],
					'type'=>$value['type'],
					'typeName'=>self::getTypeName($value['type']),
					'active' => false,
				);
		}		

		return $result;
	}

	public static function revoke($userid,$fromid,$name)
	{
		$data = array(':itemname'=>$name,':userid'=>$userid,':fromid'=>$fromid);
		$sql = "DELETE FROM {{rbac_authassignment}} WHERE itemname=:itemname AND userid=:userid AND fromid=:fromid ";
		// $sql = strtr($sql, $data);		
		$result =  Tak::getDb('db')->createCommand($sql)->execute($data);
		return $result>0;
	}

	public static function getSelectOptions($model)
	{
		$_authorizer = self::getAuthorizer();
		$_authorizer->attachUserBehavior($model);
		$assignedItems = $_authorizer->getAuthItems(null, $model->getId());
		$assignments = array_keys($assignedItems);
		$assignSelectOptions = Rights::getAuthItemSelectOptions(null, $assignments);
		return $assignSelectOptions;
	}

	public static function create($userid,$fromid,$name)
	{
		$data = array(':itemname'=>$name,':userid'=>$userid,':fromid'=>$fromid);
		$sql = "INSERT INTO {{rbac_authassignment}} (itemname, userid, fromid) VALUES(:itemname,:userid,:fromid)";
		// $sql = strtr($sql, $data);		
		 $result =  Tak::getDb('db')->createCommand($sql)->execute($data);
		return $result===1;
	}

	public static function getObj($name)
	{
		$sql = "SELECT * FROM {{rbac_authitem}} WHERE name=:itemname AND (fromid=:fromid OR fromid=0)";

		$data =  Tak::getDb('db')->createCommand($sql)->queryRow(TRUE,array(':fromid'=>Tak::getFormid(),':itemname'=>$name));
		// Tak::KD($data,1);
		return $data;
	}
}