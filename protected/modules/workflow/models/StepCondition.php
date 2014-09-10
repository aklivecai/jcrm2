<?php
/** 
 * 这个模块来自表 "oawork_step_condition".
 *
 * 数据表的字段 'oawork_step_condition':
 * @property integer $con_id
 * @property integer $step_id
 * @property integer $field_id
 * @property string $type
 * @property string $value
 */
class StepCondition extends DbiRecod {
    public static $table = 'oawork_step_condition';
    public function primaryKey() {
        return 'con_id';
    }
    public function rules() {
        return array(
            array(
                'step_id, field_id, type, value',
                'required'
            ) ,
            array(
                'step_id, field_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'type',
                'length',
                'max' => 255
            ) ,
            array(
                'value',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'con_id, step_id, field_id, type, value',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'con_id' => '条件id',
            'step_id' => '步骤 ',
            'field_id' => '字段',
            'type' => '条件',
            'value' => '值',
        );
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
