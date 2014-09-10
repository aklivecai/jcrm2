<?php

class ContactController extends Controller
{
	public function init()  
	{     
	    	parent::init();
    		$this->modelName = 'Contact';
	}

	public function actionAdminGroup(){
		$m = $this->modelName;
		$model = new $m('search');
		$model->unsetAttributes(); 
		if(isset($_GET[$m])){
			$model->attributes = $_GET[$m] ;
		}
		$this->render('adminGroup',array(
			'model'=>$model,
		));
	}
}
