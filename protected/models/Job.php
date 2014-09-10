<?php
class Job extends JRecord
{
	public $moduleid = 9;
	public function init(){
		parent::init();
	}
	public function tableName()
	{
		return '{{job}}';
	}	
	public function rules()
	{
		$result = parent::rules();
		$result = array_merge_recursive($result,
			 array(
					array('itemid,', 'safe', 'on'=>'search'),
			)
		);
		return $result;
	}	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}	
	public function attributeLabels()
	{
		$result = parent::attributeLabels();
		$result['content'] = "要求";
		$result = array_merge_recursive($result,
			 array(
				
			)
		);
		return $result;
	}	
		//保存数据前
	protected function beforeSave(){
	    $result = parent::beforeSave();
	    return $result;
	}
	protected function afterSave(){
		parent::afterSave();
	}		

}
