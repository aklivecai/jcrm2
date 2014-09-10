<?php
/**
 * 用于正在进行的工作流信息（开始时间,结束时间,状态，发起人等）
 */
class FlowRunPrc extends DbiRecod {
    public $handel_time = 0;
    public $timeout = 0;
    public $start_time = 0;
    public static $table = 'oawork_flow_run_prc';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function rules() {
        return array(
            array(
                "run_id, step_name, step_user, step_no",
                "required"
            ) ,
            
            array(
                'run_id,step_user,handel_time',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function relations() {
        return array(
            'flow_run' => array(
                self::BELONGS_TO,
                'FlowRun',
                'run_id'
            ) ,
        );
    }
    public function attributeLabels() {
        return array();
    }
    
    public function search($user) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('handel_time>0');
        $criteria->addCondition('step_no > 0');
        $criteria->addCondition('step_user = :user');
        $criteria->params[':user'] = $user;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
