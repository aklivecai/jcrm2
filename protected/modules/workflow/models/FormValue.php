<?php
/**
 * 流程表单的值
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-11 09:30:57
 * @version $Id$
 */
class FormValue extends DbiRecod {
    public static $table = 'oawork_form_value';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'id';
    }
    public function rules() {
        return array(
            array(
                'run_id,field_id,value,form_id',
                'required'
            ) ,
            array(
                'run_id, field_id,form_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'id, run_id, field_id, value',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'id' => '值id ',
            'form_id' => '对应表单id', //(方便删除的时候统一删除)
            'run_id' => '进程id',
            'field_id' => '字段id',
            'value' => '对应流程的字段值',
        );
    }
}
?>