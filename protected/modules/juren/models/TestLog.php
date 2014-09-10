<?php

class TestLog extends AdminLog
{
	public function attributeLabels(){
		$arr = parent::attributeLabels();
		$arr['fromid'] = '会员编号';
		$arr['user_name'] = '操作人';
		return $arr;
	}
    public function getPageSize(){
		if (isset($_GET['setPageSize'])) {
			$setPageSize = (int)$_GET['setPageSize'];
			if ($setPageSize>=0
				&&$setPageSize!=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])
				) {
				Yii::app()->user->setState('pageSize',$setPageSize);
			}			
			unset($_GET['pageSize']); 
			$pageSize = $setPageSize;
		}else{
			$pageSize = Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);
		}
		return $pageSize;
    }
	public function search()
	{
		$criteria=new CDbCriteria;

		$pageSize = $this->getPageSize();
		$criteria->compare('itemid',$this->itemid);
		$criteria->compare('fromid',$this->fromid);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('qstring',$this->qstring,true);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('ip',$this->ip,true);
		if ($this->add_time!='') {
			$time = $this->add_time ;
			if (!is_numeric($time)) {
				$time = strtotime($time);
			}
			$time = Tak::isTimestamp($time);
			if ($time) {
				$end = Tak::getDayEnd($time);
				$criteria->addBetweenCondition('add_time',$time,$end);
			}
		}
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array( 
				'pageSize' => $pageSize, 
			), 			
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}	

	//默认继承的搜索条件
    public function defaultScope()
    {
    	$arr = array();
    	$arr['order'] = 'add_time DESC';
    	$condition = array();
    	// 查找自己会员的东西
    	$uname = Yii::app()->user->name; 
    	$manageid = Tak::getManageid();
    	if ($uname!='admin') {
    		$condition[] = 'fromid IN (SELECT itemid FROM {{test_memeber}} WHERE manageid='.$manageid.')';	
    	}
    	
    	$condition[] = "manageid!='$manageid'";

    	$arr['condition'] = implode(" AND ",$condition);
    	return $arr;
    }
}
