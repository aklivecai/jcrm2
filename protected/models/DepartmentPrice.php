<?php
/**
 *车间工序价格
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-07-01 16:59:20
 * @version $Id$
 */

class DepartmentPrice extends DbiRecod {
    public static $table = '{{department_price}}';
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
                ' department_id, name,price',
                'required'
            ) ,
            array(
                'department_id, itemid, fromid',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'price',
                'numerical',
                'min' => 1,
            ) ,
            array(
                'department_id, itemid, fromid',
                'length',
                'max' => 25
            ) ,
            array(
                'name',
                'length',
                'max' => 200
            ) ,
            array(
                'name',
                'checkRepetition'
            ) ,
            array(
                'name,price',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    protected function getRepetition($attribute) {
        $result = array();
        if ($attribute == 'name') {
            $result[] = sprintf('department_id=%s', $this->department_id);
        }
        return $result;
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '企业编号',
            'department_id' => '车间编号',
            'name' => '工序或产品名',
            'price' => '价格',
        );
    }
    //默认继承的搜索条件
    public function search($pagesize=10) {
        $criteria = new CDbCriteria;
        $criteria->compare('department_id', $this->department_id);
        $criteria->compare('name', $this->name, true);
        if ($this->price > 0) {
            $criteria->compare('price', $this->price, true);
        }
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pagesize,
            ) ,
        ));
    }
    public function defaultScope() {
        return array(
            'condition' => 'fromid=' . Ak::getFormid() ,
            'order' => 'itemid DESC ',
        );
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($this->isNewRecord) {
            $this->itemid = Ak::fastUuid();
            $this->fromid = Ak::getFormid();
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
}
