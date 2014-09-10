<?php
/**
 * 
 */
class FlowRun extends DbiRecod {
    public static $table = 'oawork_flow_run';
    public $flow_name = '';
    public $end_time = 0;
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'run_id';
    }
    public function rules() {
        return array(
            array(
                'flow_id, begin_user, flow_name, run_state',
                'required'
            ) ,
            array(
                'run_state',
                'numerical'
            ) ,
            
            array(
                'flow_id,flow_name,run_state,begin_user',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'run_prc' => array(
                self::HAS_MANY,
                'FlowRunPrc',
                'run_id',
                'order' => 'run_prc.prc_id desc'
            ) ,
            'flow_info' => array(
                self::BELONGS_TO,
                'FlowInfo',
                'flow_id'
            ) ,
            'prc_data' => array(
                self::HAS_ONE,
                'FormModel',
                'run_id'
            ) ,
        );
    }
    /**
     * @return array customized attribute labels (name=&gt;label)
     */
    public function attributeLabels() {
        return array();
    }
    
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        
        $criteria = new CDbCriteria;
        $criteria->compare('flow_id', $this->flow_id);
        $criteria->compare('run_state', $this->run_state);
        $criteria->compare('begin_user', $this->begin_user);
        $criteria->with = array(
            'prc_data',
            'run_prc'
        );
        // $criteria->order = 't.run_id desc, run_prc.prc_id desc';
        if (!empty($this->run_prc)) {
            $criteria->together = TRUE;
            $criteria->compare('run_prc.handel_time', $this->run_prc['handel_time']);
            $criteria->compare('run_prc.step_user', $this->run_prc['step_user']);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function searchBy($username) {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $sql = sprintf("t.run_id IN(SELECT run_id FROM %s WHERE step_no>0 AND step_user='%s' AND handel_time>0 GROUP BY run_id )", 'oawork_flow_run_prc', $username);
        $criteria->addCondition($sql);
        $criteria->with = array(
            'prc_data',
            'run_prc'
        );
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
