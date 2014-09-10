<?php

class Jb2bCompany extends Jb2bRecord
{
	private $scondition = false;/*默认搜索条件*/
	public $linkName = 'company';
	public static $table = '{{company}}';

	public function primaryKey()
	{
		 return 'userid';
	}	

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	   public function rules()
	    {
	        return array(
	            array('userid,username,groupid,company,vip,telephone,address,', 'safe', 'on'=>'search'),
	        );
	    }

    	//默认继承的搜索条件
    public function defaultScope()
    {
		$arr = array('order'=>'vip DESC');
		$condition = array();    	
		$condition[] = 'groupid>5';
		$arr['condition'] = implode(" AND ",$condition);

		return $arr;	
    }

    public function attributeLabels()
    {
        return array(
            'userid' => '会员ID',
            'groupid' => '会员组',
            'company' => '公司名',
            'vip' => 'VIP级别',
            'username' => '会员名',
        );
    }    


	public function search()
	{

		$cActive = parent::search();
		$criteria = $cActive->criteria;	

		$criteria->compare('userid',$this->userid);
		$criteria->compare('username',$this->username,1);
		$criteria->compare('company',$this->company,1);

		return $cActive;

	}    
}
