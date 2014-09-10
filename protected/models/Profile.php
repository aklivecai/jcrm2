<?php
class Profile extends CActiveRecord {
    /**
     * @return string 数据表名字
     */
    public static $table = '{{profile}}';
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    /**
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'company,user_nicename,address',
                'required'
            ) ,
            array(
                'sex',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid',
                'length',
                'max' => 25
            ) ,
            array(
                'company',
                'length',
                'max' => 100
            ) ,
            array(
                'user_nicename',
                'length',
                'max' => 64
            ) ,
            array(
                'telephone, mobile, fax',
                'length',
                'max' => 50
            ) ,
            array(
                'address',
                'length',
                'max' => 255
            ) ,
            array(
                'itemid, sex, company, user_nicename, telephone, mobile, address, fax',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'telephone',
                'cheTel',
            ) ,
            array(
                'mobile',
                'cheMobile',
            ) ,
            array(
                'telephone',
                'cheTelMobile',
            ) ,
        );
    }
    public function cheTelMobile($attribute, $params) {
        $tel = $this->$attribute;
        if ($tel == '' && $this->mobile == '') {
            $this->addError($attribute, '电话和手机必须填写一个!');
        }
    }
    
    public function cheTel($attribute, $params) {
        $tel = $this->$attribute;
        if ($tel != '') {
            if (preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)$/', $tel)) {
            } else {
                $this->addError($attribute, '电话号码格式不对,如:0755-8888888');
            }
        }
    }
    public function cheMobile($attribute, $params) {
        $tel = $this->$attribute;
        if ($tel != '') {
            if (preg_match("/^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/", $tel)) {
            } else {
                $this->addError($attribute, '手机号码格式不对    !');
            }
        }
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
            'itemid' => '编号',
            'sex' => '性别', /*(0:不公开,1:男,2:女)*/
            'company' => '名称', /*(公司或者个人)*/
            'user_nicename' => '联系人',
            'telephone' => '电话',
            'mobile' => '手机',
            'address' => '地址',
            'fax' => '传真',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('itemid', $this->itemid, true);
        $criteria->compare('sex', $this->sex);
        $criteria->compare('company', $this->company, true);
        $criteria->compare('user_nicename', $this->user_nicename, true);
        $criteria->compare('telephone', $this->telephone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('fax', $this->fax, true);
        return $cActive;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($itemid = false) {
        if (!$itemid) {
            $itemid = Tak::getManageid();
        }
        return self::model()->findByPk($itemid);
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
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave(false);
        if ($result) {
            $this->itemid = Tak::getManageid();
        }
        return $result;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
        $m = Manage::model()->findByPk($this->itemid);
        $m->user_nicename = $this->company;
        $m->save();
        Tak::setFlash('操作成功！');
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
}
