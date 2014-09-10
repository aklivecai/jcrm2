<?php
class TestMemeber extends ModuleRecord {
    
    public $status = 1;
    public $linkName = 'company';
    public static $table = '{{test_memeber}}';
    
    public $user_name = 'admin';
    /**
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'company',
                'required'
            ) ,
            array(
                'active_time, start_time, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 20
            ) ,
            array(
                'company',
                'length',
                'max' => 64
            ) ,
            array(
                'user_name',
                'length',
                'max' => 60
            ) ,
            array(
                'email',
                'length',
                'max' => 100
            ) ,
            array(
                'logo',
                'length',
                'max' => 255
            ) ,
            array(
                'add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, manageid, company, email,logo, active_time, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note',
                'safe',
                'on' => 'search'
            ) ,
            
            array(
                'active_time,start_time',
                'checkTime'
            ) ,
        );
    }
    /**
     * @return array relational rules. 表的关系，外键信息
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }
    /**
     * @return array customized attribute labels (name=>label) 字段显示的
     */
    public function attributeLabels() {
        return array(
            'itemid' => '会员编号',
            'manageid' => '管理员编号',
            'user_name' => '默认登陆帐号',
            'company' => '公司名字',
            'email' => '邮箱',
            'logo' => '企业Logo',
            'active_time' => '开始计算日期',
            'start_time' => '激活时间',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'status' => '状态',
            'note' => '备注',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('manageid', $this->manageid);
        $criteria->compare('company', $this->company, true);
        $criteria->compare('email', $this->email, true);
        
        $this->setCriteriaTime($criteria, array(
            'add_time',
            'active_time',
            'modified_time'
        ));
        foreach (array(
            'add_us',
            'add_ip',
            'modified_us',
            'modified_ip'
        ) as $col) {
            $v = $this->$col;
            if ($v > 0) {
                $criteria->compare($col, $v);
            }
        }
        $criteria->compare('note', $this->note, true);
        return $cActive;
    }
    
    public static function proc() {
        $uname = Yii::app()->user->name;
        if ($uname != 'admin') {
            $manageid = Tak::getManageid();
            $condition[] = "manageid=$manageid";
        }
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = array();
        $arr['order'] = 'add_time DESC';
        $condition = array(
            'status=1'
        );
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    // 继承
    public function getHtmlLink($isbtn = true, $itemid = false, array $htmlOptions = array() , $action = 'view') {
        $key = Tak::setCryptNum($this->itemid);
        $htmlOptions = array(
            'class' => 'copy',
            'id' => $this->itemid
        );
        //$url = Yii::app()->request->hostInfo.'/?'.$key;
        $url = Yii::app()->request->hostInfo . Yii::app()->getBaseUrl() . '/?' . $key;
        
        $link = CHtml::link($url, $url, array(
            'target' => '_blank',
            'title' => '点击打开'
        ));
        if ($isbtn) {
            $link.= CHtml::button('复制', $htmlOptions);
        }
        // $link .= 'asd'.CHtml::button('复制',$htmlOptions);
        return $link;
    }
    
    public function getMmeber($itemid) {
        $sql = "SELECT * FROM {$this->tableName() }
            WHERE itemid = :itemid ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':itemid', $itemid);
        $tags = $command->queryRow();
        if (!$tags) {
            $tags = false;
        }
        return $tags;
    }
    //保存数据前
    protected function beforeSave() {
        //添加数据时候
        if ($this->isNewRecord) {
            $this->itemid = 1;
        } else {
            //修改数据时候           
            
        }
        
        $result = parent::beforeSave();
        return $result;
    }
    
    public function del() {
        $result = false;
        if ($this->status != TakType::STATUS_DELETED) {
            $this->status = TakType::STATUS_DELETED;
            if ($this->save()) {
                $result = true;
            } else {
                $arr = $this->getErrors();
            }
        }
        return $result;
    }
}
