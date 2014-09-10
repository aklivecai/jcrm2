<?php
class News extends JRecord
{
	public $moduleid = 1001;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{news}}';
	}

	public function tags(){
		$cActive = parent::search();
		$criteria = $cActive->criteria;
		return $cActive;
	}
	
}