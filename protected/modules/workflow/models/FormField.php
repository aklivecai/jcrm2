<?php
/**
 * 表单中自定义字段信息
 */
class FormField extends DbiRecod {
    public static $table = 'oawork_form_field';
    public $dvalue = '';
    public $bindfunction = "";
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function rules() {
        return array(
            array(
                'form_id, field_name, otype,  odata, html',
                'required'
            ) ,
            array(
                'form_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'field_name',
                'length',
                'max' => 225
            ) ,
            array(
                'otype',
                'length',
                'max' => 11
            ) ,
            array(
                'dvalue, style',
                'length',
                'max' => 255
            ) ,
            array(
                'bindfunction',
                'length',
                'max' => 100
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'field_name, otype, dvalue, style, odata, html, bindfunction',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function relations() {
        return array(
            'formInfo' => array(
                self::BELONGS_TO,
                'FormInfo',
                'form_id'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'field_id' => '字段id',
            'form_id' => '对应表单id',
            'field_name' => '字段显示名称',
            'otype' => '字段类型',
            'dvalue' => '默认值',
            'style' => '字段样式',
            'odata' => '字段数据详细',
            'html' => 'html内容',
            'bindfunction' => '触发JS函数',
        );
    }
}
