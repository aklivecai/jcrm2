<?php

/**
 * 这个模块来自表 "{{history}}".
 *
 * 数据表的字段 '{{history}}':
 * @property string $itemid
 * @property string $manageid
 * @property string $sid
 * @property string $sname
 * @property string $add_time
 * @property string $last_time
 * @property string $note
 * @property integer $count
 * @property integer $status
 * @property integer $typeid
 */
class History extends MRecord
{
	public static $table = '{{history}}';

	/**
	 * @return array validation rules for model attributes.字段校验的结果
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid', 'required'),
			array('count, status, typeid', 'numerical', 'integerOnly'=>true),
			array('itemid, manageid', 'length', 'max'=>25),
			array('sid, add_time, last_time', 'length', 'max'=>10),
			array('sname, note', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('itemid, manageid, sid, sname, add_time, last_time, note, count, status, typeid', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules. 表的关系，外键信息
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label) 字段显示的
	 */
	public function attributeLabels()
	{
		return array(
				'itemid' => '编号',
				'manageid' => '会员ID',
				'sid' => '来源编号',
				'sname' => '来源名字',
				'add_time' => '添加时间',
				'last_time' => '最后浏览时间',
				'note' => '备注',
				'count' => '浏览次数',
				'status' => '状态', /*(0:锁定,1:正常)*/
				'typeid' => '分类', /*(1:企业)*/
		);
	}

	public function search()
	{
		$cActive = parent::search();
		$criteria = $cActive->criteria;

		$criteria->compare('itemid',$this->itemid,true);
		$criteria->compare('manageid',$this->manageid,true);
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('sname',$this->sname,true);
		if ($this->add_time) {
			if (is_numeric($this->add_time)) {
				$criteria->compare('add_time',$this->add_time);
			}else{
				$date1 = strtotime($this->add_time);
				$date2 = UTak::getDayEnd($date1);
				if ($date2) {
					$criteria->addBetweenCondition('add_time', $date1, $date2);
				}
			}
		}
		if ($this->last_time) {
			if (is_numeric($this->last_time)) {
				$criteria->compare('last_time',$this->last_time);
			}else{
				$date1 = strtotime($this->last_time);
				$date2 = UTak::getDayEnd($date1);
				if ($date1) {
					$criteria->addBetweenCondition('last_time', $date1, $date2);
				}
			}
		}
		
		$criteria->compare('note',$this->note,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('status',$this->status);
		$criteria->compare('typeid',$this->typeid);
		return $cActive;
	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//默认继承的搜索条件
    public function defaultScope()
    {
    	$arr = parent::defaultScope();
    	$condition = array();
    	if (isset($arr['condition'])) {
    		$condition[] = $arr['condition'];
    	}
    	$condition[] = 'manageid='.Tak::getManageid();
    	$arr['condition'] = implode(" AND ",$condition);
    	return $arr;
    }

	//保存数据前
	protected function beforeSave(){
	    $result = parent::beforeSave();
	    if($result){
	        //添加数据时候
	        if ($this->isNewRecord){
	        	if (!$this->manageid) {
	        		$this->manageid = Tak::getManageid();
	        	}        	
	        }else{
	        	//修改数据时候
	        }
	    }
	    return $result;
	}

	//保存数据后
	protected function afterSave(){
		parent::afterSave();
	}	

	//删除信息后
	protected function afterDelete(){
		parent::afterDelete();
	}	

	public static function addCount($sid){
        $member = TestMemeber::model()->findByPk($sid);       
        if ($member!==null) {
            $m = self::model()->findByAttributes(array('sid'=>$sid));
            $time = time();
            if ($m!==null){
                $date = $time - $m->last_time ;
                $date = bcdiv($date,60);
                if ($date>30) {
                    $m->last_time = $time;
                    $m->count += 1;
                    $m->save();
                }
            }else{
            if(UTak::isGuest()){
                $session = Yii::app()->session;
                $session->add('uid',Tak::fastUuid());
                $manageid = $session->get('uid');
            }else{
                $manageid = false; 
            }
                $m = new History; 
                $m->sid = $member->itemid;
                $m->sname = $member->company;
                $m->manageid =$manageid;
                $m->add_time = $time;
                $m->last_time = $time;
                $m->count = 1;
                $m->save();
            }
        }		
	}
    public static function upMCount(){
        $session = Yii::app()->session;
        $manageid = Yii::app()->session->get('uid');
        $list = History::model()->findAllByAttributes(array('manageid'=>$manageid));
        $time = time();
        if (count($list)>0) {
            foreach ($list as $key => $value) {
              $m = History::model()->findByPk($value->itemid);    
              if ($m) {
                $m->count += $value->count;
                $m->last_time = $time;
                $m->save();
                $value->delete();
              }else{
                $value->manageid = Tak::getManageid();
                $value->save();
              }
            }
        }
    }

}
