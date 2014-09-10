<?php
class FlowCond extends DbiRecod {
    public static $table = 'oawork_step_condition';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'con_id';
    }
    public function rules() {
        return array(
            array(
                'value,type,field_id',
                'required'
            ) ,
        );
    }
    public function relations() {
        return array(
            'step_info' => array(
                self::BELONGS_TO,
                'FlowStep',
                'step_id'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'value' => '限值',
            'field_id' => '表单字段',
            'type' => '条件',
        );
    }
    
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('step_id', $this->step_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
