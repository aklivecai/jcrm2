<?php

class TakTypeController extends Controller
{
	public $type = false;
	public $_model;
	public $typeUrl = false;
	public function init()  
	{     
		parent::init();
	    	$this->modelName = 'TakType';
	    	$this->primaryName = 'typeid';
	    	$type = Yii::app()->request->getParam('type',false);
	    	$arr = Tak::getTakTypes();
	    	if ($type&&isset($arr[$type])) {
				if($arr[$type]){
					$this->type = $arr[$type];
				}
				$this->typeUrl = $this->createUrl('admin',array('type'=>$this->type['type']));
				return ;
	    	}
	    	$this->error();
	}
	public function loadModel($id=false,$not=false)
	{
		if($this->_model===null)
		{
			if ($id) {
				$m = $this->modelName;
				$model = $m::model();
				$this->_model = $model->getObj($id,$this->type['type']);
			}
			if($this->_model===null){
					throw new CHttpException(404,'所请求的页面不存在。');
			}else{
				$this->_model->initak($this->type);
			}
		}
		return $this->_model;
	}

	public function actionCreate(){

	}

	public function actionDelete($id)
	{

		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->typeUrl);
	}		

	public function actionAdmin($id=false){
		$m = $this->modelName;
		$listM = new $m('search');
		$listM->initak($this->type);
		$model = false;
		if ($id) {
			$model = $this->loadModel($id);
		}
		if (!$model) {
			$model = new $m;
			$model->item = $this->type['type'];
		}
		if (Yii::app()->request->getParam('returnUrl',false)) {
			$this->typeUrl = $returnUrl = Yii::app()->request->getParam('returnUrl');
		}
		if(isset($_POST[$m]))
		{

			$model->attributes = $_POST[$m];
			$model->initak($this->type);
			if($model->save()){
				$returnUrl = $_POST['returnUrl'];
				if (!$returnUrl) {
					if ($this->isAjax) {
						if ($_POST['getItemid']) {
							echo $model->primaryKey;
							exit;
						}
					}
					$this->redirect($this->typeUrl);
				}else{
					$this->redirect($returnUrl);	
				}				
			}
		}elseif(isset($_GET[$m])){
			$model->attributes = $_GET[$m] ;
		}		

		$this->render('admin',array(
			'listM'=>$listM,
			'model'=>$model,
		));		
	}
}
