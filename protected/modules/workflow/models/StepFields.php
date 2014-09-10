<?php
class StepFields extends DbiRecod {
    public static $table = 'oawork_step_fields';
    public $show = 0;
    public $hide = 1;
    public $write = 0;
    public $must = 0;
    public function primaryKey() {
        return 'id';
    }
    public function rules() {
        return array(
            array(
                'step_id, field_id',
                'required'
            ) ,
            array(
                'step_id, field_id, sfrom, show, hide, write, must',
                'numerical',
                'integerOnly' => true
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, step_id, field_id, sfrom, show, hide, write, must',
                'safe',
                'on' => 'search'
            ) ,
            array(
                ' show, hide, write, must',
                'checkIsTrue'
            ) ,
        );
    }
    
    public function checkIsTrue($attribute, $params) {
        $this->$attribute = $this->$attribute == 1 ? 1 : 0;
        return true;
    }
    
    public function attributeLabels() {
        return array(
            'id' => '步骤字段id',
            'step_id' => '步骤id',
            'field_id' => '字段id',
            'sfrom' => '来源',
            'show' => '显示',
            'hide' => '隐藏',
            'write' => '可写',
            'must' => '必填',
        );
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
