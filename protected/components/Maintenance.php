<?php

Yii::import('zii.widgets.CPortlet');

class Maintenance extends CPortlet
{
	public function init()
	{
		$this->title='Maintenance';
		parent::init();
	}

	protected function renderContent()
	{
		$this->render('maintenance');
	}
}