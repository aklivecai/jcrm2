<?php
class Merchandiser extends TRecord {
    public static $table = '{{merchandiser}}';
    public function rules() {
        return array(
            array(
                'manageid,mid,item',
                'required'
            ) ,
            array(
                'fromid',
                'required',
                'on' => 'update'
            ) ,
            array(
                'manageid,mid,fromid,add_time',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'manageid,mid',
                'length',
                'max' => 25
            ) ,
            array(
                'item',
                'length',
                'max' => 20
            ) ,
            array(
                'fromid, add_ip',
                'length',
                'max' => 10
            ) ,
            array(
                ' fromid, manageid, item',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        $result = array(
            'manageid' => '用户编号',
            'mid' => '归属用户编号',
            'fromid' => "平台会员编号",
            'item' => "类型",
            'add_time' => "添加时间",
        );
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            if ($this->isNewRecord) {
            }
        }
    }
}
