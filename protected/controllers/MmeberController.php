<?php

class MmeberController extends MController
{
	public $layout = 'column2';

	public function filters()
	{
		return array(
			'updateOwn + update', 
		);
	}
	
	public function allowedActions()
	{
	 	return 'login, error';
	}

	public function actionView()
	{

	}

	public function actionCreate()
	{

	}
}
