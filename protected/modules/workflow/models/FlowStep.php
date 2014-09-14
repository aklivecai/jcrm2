<?php
/**
 * 定义工作流的步骤信息(超时提醒)
 */
class FlowStep extends DbiRecod {
    public $step_remind = 0;
    public $timeout = 0;
    public $next_step = '';
    public $flow_id = 0;
    public $step_no = 0;
    public static $table = 'oawork_flow_step';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'step_id';
    }
    
    public function rules() {
        return array(
            array(
                'flow_id,step_name,step_user',
                'required'
            ) ,
            array(
                'flow_id,order,timeout',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'next_step,order',
                'safe'
            ) ,
            array(
                'step_name,timeout,step_user',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'step_name',
                'checkRepetition'
            ) ,
            array(
                'step_user',
                'checkMid'
            ) ,
        );
    }
    
    protected function getRepetition($attribute) {
        $result = array();
        if ($attribute == 'step_name') {
            $result[] = sprintf(' flow_id=%s', $this->flow_id);
        }
        return $result;
    }
    public function relations() {
        return array(
            // 'flow' => array(self::BELONGS_TO, ''),
            'condition' => array(
                self::HAS_MANY,
                'FlowCond',
                'step_id'
            ) ,
        );
    }
    /**
     * @return array customized attribute labels (name=&gt;label)
     * $DATA[步骤名称]
     */
    public function attributeLabels() {
        return array(
            'step_id' => '流程步骤id',
            'flow_id' => '流程id',
            'step_no' => '步骤号',
            'step_name' => '步骤名称',
            'step_user' => '步骤处理人',
            'step_remind' => '提醒标记', /*(未使用)*/
            'timeout' => '设置步骤超时提醒',
            'order' => '排序', /*(未使用)*/
            'note' => '备注',
        );
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('flow_id', $this->flow_id);
        $criteria->with = array(
            'condition'
        );
        // $criteria->compare('step_no', $this->step_no);
        // $criteria->compare('step_name', $this->step_name);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    protected function beforeSave() {
        $this->timeout == '' && $this->timeout = 0;
        return true;
    }
    /**
     * 新增成功以后，默认插入第一步骤
     * @return [type] [description]
     */
    protected function afterSave() {
        parent::afterSave();
        //激活编号
        if ($this->isNewRecord) {
            $this->_add();
        }
    }
    protected function afterDelete() {
        parent::afterDelete();
        $this->_del();
    }
    private function _add() {
        $data = array(
            ':table' => self::$table,
            ':flow_id' => $this->flow_id,
            ':step_id' => $this->primaryKey,
        );
        $sql = 'SELECT COUNT(1) FROM :table WHERE flow_id=:flow_id';
        $sql = strtr($sql, $data);
        $command = self::$db->createCommand($sql);
        $data[':step_no'] = $command->queryScalar();
        $sql = 'UPDATE  :table SET step_no=:step_no WHERE flow_id=:flow_id AND step_id=:step_id';
        $sql = strtr($sql, $data);
        $command->text = $sql;
        $rowCount = $command->execute();
    }
    /**
     * 更新步骤编号，步骤后面的全部-1
     * @return [type] [description]
     */
    private function _del() {
        $data = array(
            ':table' => self::$table,
            ':flow_id' => $this->flow_id,
            ':step_id' => $this->primaryKey,
        );
        $sql = 'UPDATE  :table SET step_no=step_no-1 WHERE flow_id=:flow_id AND step_id>:step_id';
        $sql = strtr($sql, $data);
        $command = self::$db->createCommand($sql);
        $count = $command->query();
        //删除步骤中的条件
        StepCondition::model()->deleteAll('step_id=' . $this->primaryKey);
        //删除字段的属性
        $this->delFields($this->primaryKey);
    }
    /**
     * 更新条件数
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function upConditions($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        $data = array(
            ':table' => self::$table,
            ':StepCondition' => StepCondition::$table,
            ':step_id' => $id,
        );
        $sql = 'SELECT COUNT(1)   FROM :StepCondition WHERE step_id=:step_id';
        $sql = strtr($sql, $data);
        // Tak::KD($sql,1);
        $row = self::$db->createCommand($sql)->queryScalar();
        $data[':rows'] = $row;
        
        $sql = 'UPDATE  :table SET conditions=:rows WHERE  step_id=:step_id';
        $sql = strtr($sql, $data);
        $command = self::$db->createCommand($sql);
        $command->query();
    }
    
    public function delFields($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        //删除步骤中的控件
        StepFields::model()->deleteAll('step_id=' . $this->primaryKey);
    }
    public function getFieldsBySql($id = 0, $endSql = false) {
        $id == 0 && $id = $this->primaryKey;
        $data = array(
            ':table' => StepFields::$table,
            ':fid' => $id,
        );
        $sql = "SELECT `field_id`,`show`,`hide`,`write`,`must` FROM :table  WHERE step_id=:fid ";
        if ($endSql) {
            $sql.= ' AND ' . $endSql;
        }
        $sql = strtr($sql, $data);
        // TaK::KD($sql,1);
        $fiels = self::$db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($fiels as $key => $value) {
            $result[$value['field_id']] = $value;
        }
        return $result;
    }
    /**
     * 获取当前可以录入的字段信息
     * @return [type] [description]
     */
    public function getWFields() {
        return $this->getFieldsBySql(0, '`write`=1');
    }
    
    public function isFirst() {
        return $this->step_no == 1;
    }
    
    public function getConditionsBySql($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        $data = array(
            ':table' => StepCondition::$table,
            ':fid' => $id,
        );
        $sql = "SELECT con_id,field_id,html FROM :table  WHERE step_id=:fid ";
        $sql = strtr($sql, $data);
        // TaK::KD($sql);
        $fiels = self::$db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($fiels as $key => $value) {
            $result[$value['con_id']] = $value;
        }
        return $result;
    }
}
