<?php
class FlowInfo extends DbiRecod {
    public $form_id = 0;
    public $status = 1;
    public static $table = 'oawork_flow_info';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'flow_id';
    }
    public function rules() {
        return array(
            array(
                'flow_name,status',
                'required'
            ) ,
            array(
                'flow_name',
                'checkRepetition'
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
        );
    }
    public function relations() {
        return array(
            'flow_run' => array(
                self::HAS_MANY,
                'FlowRun',
                'flow_id'
            ) ,
            'formInfo' => array(
                self::BELONGS_TO,
                'FormInfo',
                'form_id'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'flow_name' => '流程名称',
            'form_id' => '表单id',
            'note' => '备注',
            'status' => '状态',
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $condition = array();
        if ($this->hasAttribute('fromid')) {
            $condition[] = 'fromid=' . Ak::getFormid();
        }
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        $arr = Ak::getOM();
        if ($this->isNewRecord) {
            $this->fromid = $arr['fromid'];
            $this->add_us = $arr['manageid'];
            $this->add_time = $arr['time'];
            $this->add_ip = $arr['ip'];
        } else {
            //修改数据时候
            $this->modified_us = $arr['manageid'];
            $this->modified_time = $arr['time'];
            $this->modified_ip = $arr['ip'];
        }
        return $result;
    }
    /**
     * 新增成功以后，默认插入第一步骤
     * @return [type] [description]
     */
    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $this->initOne();
        }
    }
    private function initOne() {
        //插入第一步
        $stepmodel = new FlowStep;
        $stepmodel->step_user = 0;
        $stepmodel->step_name = '申请';
        $stepmodel->step_no = 1;
        $stepmodel->flow_id = $this->primaryKey;
        $stepmodel->save();
        //新建一个表单
        $formmodel = new FormInfo;
        $formmodel->form_id = $this->primaryKey;
        $formmodel->form_name = $model->flow_name . '表单';
        $formmodel->save();
        //新建表单关联工作流
        $this->form_id = $model->form_id;
    }
    protected function afterDelete() {
        parent::afterDelete();
        //
        $itemid = $this->primaryKey;
        FormInfo::model()->deleteByPk($itemid);
        // FlowStep::model()->deleteAll('flow_id=' . $itemid);
        $steps = $this->getFlowSteps();
        foreach ($steps as $value) {
            $value->delete();
        }
        FormField::model()->deleteAll('form_id=' . $itemid);
        FormValue::model()->deleteAll('form_id=' . $itemid);
    }
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->addBetweenCondition('status', 1, 3);
        return $cActive;
    }
    /**
     * 查询流程的第一个步骤
     * @param  integer $id 流程编号
     * @return  FlowStep     第一个步骤
     */
    public function getFirstStep($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        $sql = sprintf('flow_id=%s AND step_no=1', $id);
        return FlowStep::model()->find($sql);
    }
    public function getFlowSteps($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        $result = FlowStep::model()->findAll('flow_id=:flow_id', array(
            ':flow_id' => $id
        ));
        return $result;
    }
    
    public function getFlowStepsBySql($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        $data = array(
            ':table' => FlowStep::$table,
            ':mange' => Manage::$table,
            ':flow_id' => $id,
            ':fid' => $this->fromid,
        );
        $sql = "SELECT s.step_id,s.step_name,s.step_user,s.timeout,s.step_no FROM :table AS s WHERE s.flow_id=:flow_id ORDER BY step_no ASC ";
        $sql = strtr($sql, $data);
        // Tak::KD($sql);
        $result = self::$db->createCommand($sql)->queryAll();
        $mids = array(
            0
        );
        // user_nicename
        foreach ($result as $key => $value) {
            !isset($mids[$value['step_user']]) && $mids[$value['step_user']] = $value['step_user'];
        }
        $sql = "SELECT manageid,user_nicename FROM :mange  WHERE fromid=:fid AND manageid IN(" . implode(',', $mids) . ")";
        $sql = strtr($sql, $data);
        $users = self::$db->createCommand($sql)->queryAll();
        $uids = array();
        
        foreach ($users as $value) {
            $uids[$value['manageid']] = $value['user_nicename'];
        }
        foreach ($result as $key => $value) {
            //加密编号
            $result[$key]['step_id'] = Ak::setSId($value['step_id']);
            $result[$key]['step_user'] = Ak::setSId($value['step_user']);
            if (isset($uids[$value['step_user']])) {
                $result[$key]['step_user_name'] = $uids[$value['step_user']];
            } else {
                $result[$key]['step_user_name'] = '全部人';
            }
        }
        return $result;
    }
}
