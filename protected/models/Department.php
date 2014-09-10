<?php
/**
 *工资车间管理
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-07-01 11:13:52
 * @version $Id$
 */
class Department extends DbiRecod {
    public static $table = '{{department}}';
    public $_isLog = true;
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'itemid';
    }
    public function rules() {
        return array(
            array(
                'name',
                'required'
            ) ,
            array(
                'add_time,itemid,fromid,status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid,fromid',
                'length',
                'max' => 25
            ) ,
            array(
                'name',
                'length',
                'max' => 200
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'name,add_time,note,status',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '企业编号',
            'name' => '车间名字',
            'note' => '备注',
            'add_time' => '添加时间',
            'status' => '状态',
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        return array(
            'condition' => 'fromid=' . Ak::getFormid()
        );
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($this->isNewRecord) {
            $this->itemid = Ak::fastUuid();
            $this->fromid = Ak::getFormid();
            $this->add_time = Ak::now();
        }
        return $result;
    }
    protected function beforeValidate() {
        $result = parent::beforeValidate();
        return $result;
    }
    protected function afterSave() {   
        if ($this->isNewRecord) {
            AdminLog::log(Tk::g('Create') . $this->sName . ' - ' . $this->name);
        } else {
            AdminLog::log(Tk::g('Update') . $this->sName);
        }
    }
    public function search() {
        $criteria = parent::search();
        return $criteria;
    }
    public function del() {
        $result = false;
        if ($this->status != TakType::STATUS_DELETED) {
            $this->status = TakType::STATUS_DELETED;
            if ($this->save()) {
            } else {
                $result = $this->getErrors();
            }
        } else {
        }
        return $result;
    }
    public static function getList() {
        $db = self::$db ? self::$db : Ak::db(true);
        $sql = "SELECT itemid,name FROM :table WHERE fromid=:fromid AND status>0 ORDER BY itemid ASC";
        $data = array(
            ':table' => self::$table,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        $result = $db->createCommand($sql)->queryAll();
        return $result;
    }
}
