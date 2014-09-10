<?php

class CompanyController extends JController
{

	public $defaultAction = 'admin';
	public function init(){
		parent::init();
		$this->modelName = 'TestCompany';
		$this->layout = 'column1';
	}

	public function actionCreate()
	{
		throw new CHttpException(404,'所请求的页面不存在。');
	}
	public function actionUpdate($id)
	{
		throw new CHttpException(404,'所请求的页面不存在。');
	}
	public function actionDelete($id)
	{
		throw new CHttpException(404,'所请求的页面不存在。');
	}

	public function actionIndex()
	{
		throw new CHttpException(404,'所请求的页面不存在。');
	}
	public function actionImport($id)
	{
		
		$mcompany = $this->loadModel($id);
		 $m = Manage::model()->findByPk($id);
		
		if ($m!==null) {
			// $this->redirect(array('testMemeber/View','id'=>$id));			
		}
		$m = 'Test9Memeber';
		$model = new $m;
		if(isset($_POST[$m])){
			$model->attributes = $_POST[$m];
			if($model->validate()&&$model->save()){
				$itemid = $mcompany->userid;
				$model->upPKey($itemid);
				$this->redirect(array('/juren/testMemeber/View','id'=>$itemid));	
			}
		}else{
			$model->attributes = array(
				'itemid'=> $mcompany->userid,
				'user_name'=>$mcompany->username,
				'company'=>$mcompany->company,
			) ;
			$model->itemid = $mcompany->userid;
		}
		$this->render('import',array(
			'model' => $model,
		));	

	}

	public function actionAdmin()
	{
		$m = $this->modelName;
		$model = new $m('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET[$m])){
			$model->attributes = $_GET[$m] ;
		}
			
		$this->render('admin',array(
			'model'=>$model,
		));
	}


	public function actionTak(){
		$connection2 = Tak::getDb();

/*
	// 根据投放的vip广告生成
		$sql = " SELECT userid,company,username FROM `destoon_company` WHERE substring(linkurl,22) IN (SELECT substring(url,22) FROM `destoon_ad` WHERE `pid` = 81  ) ";		
		Tak::KD($sql);
*/
		// vip查找
		$sql = " SELECT userid,company,username FROM `destoon_company` WHERE vip>0";

		$tags = $connection2->createCommand($sql)->query()->readAll();

		$con = Tak::getDb('db');
		$_tsqls = array(
			"DELETE FROM `tak_test_memeber` WHERE 
			       `itemid` BETWEEN 2 AND 10000 AND `itemid` NOT IN(3930,4701);"
			,"DELETE FROM `tak_rbac_authassignment` WHERE 
				`fromid` BETWEEN 3 AND 10000  AND `itemname`='Admin' AND `fromid` NOT IN(3930,4701);"
			,"DELETE FROM `tak_manage` WHERE 
				`fromid` BETWEEN 3 AND 10000 AND `fromid` NOT IN(3930,4701)"
			,'DELETE FROM `tak_address_groups` WHERE 
				`fromid` BETWEEN 3 AND 10000 AND `fromid` NOT IN(3930,4701)'
			,'DELETE FROM `tak_address_book` WHERE 
				`fromid` BETWEEN 3 AND 10000 AND `fromid` NOT IN(3930,4701)'
			,'DELETE FROM `tak_type` WHERE 
				`fromid` BETWEEN 3 AND 10000 AND `fromid` NOT IN(3930,4701)'
			,'DELETE FROM {{admin_log}} WHERE 
				`fromid` BETWEEN 3 AND 10000 AND `fromid` NOT IN(3930,4701)'
		);
		foreach ($_tsqls as $sql) {
			// $con->createCommand($sql)->execute();
		}

		$companys = false;

		$tabl = 'tak_test_memeber';
		if (count($tags)>0) {
			$temp = array(1);
			foreach ($tags as $key => $value) {
				$temp[$value['userid']] = $value;
			}		
			$ids = array_keys($temp);
			$sql = "itemid IN (".implode(',',$ids).") ";
			$list = TestMemeber::model()->findAll(array('condition'=>$sql,'order'=>' itemid DESC '));
			$ids = array_flip($ids);
			foreach ($list as $key => $value) {
				unset($temp[$value->itemid]);
				unset($ids[$value->itemid]);
			}
			
			array_shift($temp);

			if (count($temp)>0) {
				$sqls = '';
				$time = Tak::now();
				$init  = new InitForm ;
				$init->username = 'admin';
				$connection = Tak::getDb('db');
				
				foreach ($temp as $key=>$value) {
					// Tak::KD($value['userid']);
					$s1 = "INSERT INTO `:tabl` (`active_time`, `add_time`, `add_us`, `add_ip`, `modified_time`, `modified_us`, `modified_ip`, `status`, `company`, `note`, `manageid`,`itemid`) VALUES (0,:time,:manageid,0,0,0,0,1,':company',':note',:manageid,:itemid); ";
					
					$sqls = strtr($s1,array(
							':itemid'=>$value['userid']
							,':manageid'=>1
							,':company'=>$value['company']
							,':note'=>$value['username']
							,':time'=>$time
							, ':tabl' => $tabl
						)
					);

					$connection->createCommand($sqls)->query();
					
					$init->fromid = $value['userid'];
					$init->install($value['username'],$value['username']);
				 	// $m = new TestMemeber;
					// Tak::KD($m->getAttributes());
					// Tak::KD($m->getErrors());
					// exit;
					// Tak::KD($sqls,1);
				}				
				$ids = array_flip($ids);
				$sql = "itemid IN (".implode(',',$ids).") ";
				// Tak::KD($sql);
				$companys = TestMemeber::model()->findAll(array('condition'=>$sql,'order'=>' itemid DESC '));
				foreach ($list as $key => $value) {
					$value->active_time = mktime(23,59,59,12,31,date("Y",$time)); 
					$value->save();
				}
			}
			// Tak::KD(count($temp));
		}
		$tags = array();
		$this->render('vip',array('tags'=>$list,'list'=>$companys));	
	}

}
