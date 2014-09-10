<?php
/**
 * This is the model class for table "{{Admin_Log}}".
 *
 * The followings are the available columns in table '{{Admin_Log}}':
 * @property long $itemid
 * @property string $fromid
 * @property string $user_name
 * @property string $qstring
 * @property string $info
 * @property string $ip
 * @property string $add_time
 */
class AdminLog extends DbiRecod {
    
    public static $isLog = true;
    
    public static $table = '{{admin_log}}';
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'itemid, manageid,fromid, user_name',
                'required'
            ) ,
            array(
                'user_name',
                'length',
                'max' => 60
            ) ,
            array(
                'qstring, info',
                'length',
                'max' => 255
            ) ,
            array(
                'add_time',
                'length',
                'max' => 10
            ) ,
            array(
                'ip',
                'safe'
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, fromid, user_name, qstring, info, ip, add_time',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员',
            'manageid' => '操作人编号',
            'user_name' => '操作人',
            'qstring' => '地址',
            'info' => '描述',
            'ip' => 'Ip',
            'add_time' => '时间',
        );
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        
        $criteria = new CDbCriteria;
        
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('manageid', $this->fromid);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('qstring', $this->qstring, true);
        $criteria->compare('info', $this->info, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('add_time', $this->add_time, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdminLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function recently($limit = 5, $pcondition = false, $order = 'add_time DESC') {
        $condition = $this->defaultScope(false);
        
        if (is_string($pcondition)) {
            $condition[] = $pcondition;
        } elseif (is_array($pcondition)) {
            $condition = array_merge_recursive($condition, $pcondition);
        }
        $criteria = new CDbCriteria(array(
            'condition' => implode(" AND ", $condition) ,
            'order' => $order
        ));
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $limit,
            ) ,
        ));
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = array();
        if (true) {
            $arr['order'] = 'add_time DESC';
        }
        $condition = array();
        
        if ($this->hasAttribute('fromid')) {
            $condition[] = 'fromid=' . Tak::getFormid();
        }
        if (!Tak::checkSuperuser()) {
            $condition[] = 'manageid=' . Tak::getManageid();
        }
        
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    //保存日志操作
    public static function log($info = '') {
        if (!self::$isLog) {
            return false;
        }
        $m = new self('create');
        $m->info = Ak::strip_nr($info);
        if (PHP_SAPI != 'cli') {
            $m->qstring = Yii::app()->request->getUrl();
        }
        
        $arr = Tak::getOM();
        $arr['user_name'] = Tak::getManame();
        if (func_num_args() > 1 && is_array(func_get_arg(1))) {
            foreach (func_get_arg(1) as $key => $value) {
                if (isset($arr[$key])) {
                    $arr[$key] = $value;
                }
            }
        }
        $m->fromid = $arr['fromid'];
        $m->manageid = $arr['manageid'];
        $m->user_name = $arr['user_name'];
        $m->itemid = $arr['itemid'];
        $m->add_time = $arr['time'];
        $m->ip = $arr['ip'];
        $m->save();
    }
}
