<?php
class ContactpPrson extends DbRecod {
    public $linkName = 'nicename';
    public static $table = '{{contactp_prson}}';
    public function rules() {
        return array(
            array(
                'nicename, clienteleid',
                'required'
            ) ,
            array(
                'sex, status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                ' add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'last_time, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'nicename',
                'length',
                'max' => 64
            ) ,
            array(
                'department, position',
                'length',
                'max' => 100
            ) ,
            array(
                'email, phone, mobile, fax, address, note',
                'length',
                'max' => 255
            ) ,
            array(
                'qq',
                'length',
                'max' => 20
            ) ,
            array(
                'itemid, fromid, manageid, clienteleid, nicename, sex, department, position, email, phone, mobile, fax, qq, address, last_time, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note, status',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    /**
     * @return array relational rules. 表的关系，外键信息
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'iClientele' => array(
                self::BELONGS_TO,
                'Clientele',
                'clienteleid',
                'condition' => '',
                'order' => ''
            ) ,
        );
    }
    
    /**
     * @return array customized attribute labels (name=>label) 字段显示的
     */
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'manageid' => '会员ID',
            'clienteleid' => '客户',
            'nicename' => '名字',
            'sex' => '性别',
            'department' => '部门',
            'position' => '职位', /*(也可以写工作工作描述)*/
            'email' => '邮箱',
            'phone' => '办公电话',
            'mobile' => '手机',
            'fax' => '传真',
            'qq' => 'QQ',
            'address' => '联系地址',
            'last_time' => '最后联系时间', /*(客户联系记录中修改)*/
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
            'status' => '状态', /*(0:回收站,1:正常)*/
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('manageid', $this->manageid);
        $criteria->compare('clienteleid', $this->clienteleid);
        $criteria->compare('nicename', $this->nicename);
        $criteria->compare('sex', $this->sex);
        $criteria->compare('department', $this->department, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('qq', $this->qq, true);
        $criteria->compare('address', $this->address, true);
        
        $this->setCriteriaTime($criteria, array(
            'last_time',
            'add_time',
            'modified_time'
        ));
        $criteria->compare('note', $this->note, true);
        $criteria->compare('status', $this->status);
        
        return $cActive;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $condition = array();
        if (isset($arr['condition'])) {
            $condition[] = $arr['condition'];
        }
        // $condition[] = 'display>0';
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }    
    public function getUrl() {
        return Yii::app()->createUrl('contactpPrson/view', array(
            'itemid' => $this->itemid,
            'title' => $this->nicename,
        ));
    }
    public function getList() {
        $m = $this->findAll(array(
            'select' => 'itemid,nicename',
        ));
        $items = CHtml::listData($m, 'itemid', 'nicename');
        return $items;
    }
        
    private $_contact = null;
    protected function getContact() {
        if ($this->_contact === null) {
            $m = Contact::model()->setGetCU();
            $m->scondition = false;
            $this->_contact = $m->findAllByAttributes(array(
                'prsonid' => $this->itemid
            ));
        }
        return $this->_contact;
    }
    public function move($manageid) {
        $this->isLog = false;
        $this->manageid = $manageid;
        $this->moveContact(array(
            'manageid' => $manageid
        ));
    }
    protected function _del() {
        $tags = $this->getContact();
        $islog = $this->isLog;
        foreach ($tags as $key => $value) {
            $value->isLog = $islog;
            $value->del();
        }
        return true;
    }
    public function del() {
        $result = parent::del();
        if ($result) {
            $this->_del();
        }
        return $result;
    }
    
    public function moveContact($arr) {
        $tags = $this->getContact();
        foreach ($tags as $key => $value) {
            $value->isLog = false;
            foreach ($arr as $k2 => $v2) {
                $value->$k2 = $v2;
            }
            $value->save();
        }
        return true;
    }
    
    public function setRestore() {
        $result = parent::setRestore();
        if ($result) {
            $tags = $this->getContact();
            foreach ($tags as $key => $value) {
                $value->isLog = false;
                $value->setRestore();
            }
        }
        return $result;
    }
}
