<?php
class FormValue extends DbiRecod {
    public static $table = 'oawork_form_value';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'v_id';
    }
    /** 
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'form_id, run_id, field_name, value',
                'required'
            ) ,
            array(
                'form_id, run_id, field_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'field_name',
                'length',
                'max' => 25
            ) ,
            array(
                'value',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'v_id, form_id, run_id, field_name, field_id, value',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'v_id' => '值id ',
            'form_id' => '对应表单id',
            'run_id' => '进程id',
            'field_name' => '字段内部名称',
            'field_id' => '字段id',
            'value' => '对应流程的字段值',
        );
    }
}
?>