<?php
/**
 *流程处理模块
 */
class Process {
    private $_info_table = 'oawork_flow_run';
    private $_prc_table = 'oawork_flow_run_prc';
    private $_allStepNo = array(); //步骤step_no,索引顺序和$allstep相同
    private $_allStepId = array(); //步骤step_id,索引顺序和$allstep相同
    private $_now = '';
    function __construct() {
        $this->_now = time();
    }
    /**
     * 生成流程,插入数据
     * 在flow_run中插入流程运行信息
     * 转交到下一步骤
     *  @return   $run_id [int]
     */
    public function creatFlow($model = "", $flow_id = 1) {
        $db_com = Yii::app()->db;
        $transaction = $db_com->beginTransaction();
        try {
            //获取流程开始步骤
            $allStep = $this->getFlowStep($flow_id);
            $step_head = $allStep[0];
            //将流程运行信息插入库中
            $sqlstm = "insert into " . $this->_info_table . "(flow_id, flow_name, begin_user, run_state, start_time,step_no) values(:flow_id , :flow_name, :begin_user, :run_state, :start_time, :step_no)";
            $command = $db_com->createCommand($sqlstm);
            $command->execute(array(
                ":flow_id" => $flow_id, //1,//$model->flow_id,
                ":flow_name" => $model->flow_name,
                ":begin_user" => $model->user, //$model->user,
                ":run_state" => 0,
                ":start_time" => $this->_now,
                ":step_no" => $step_head->step_no,
            ));
            $run_id = $db_com->getLastInsertID();
            //插入表单数据
            $flowinfo = FlowInfo::model()->findByPk($flow_id);
            $form_id = $flowinfo->form_id;
            unset($flowinfo);
            $allField = FormField::model()->findAll('form_id=:form_id', array(
                ':form_id' => $form_id
            ));
            foreach ($allField as $key => $field) {
                if (array_key_exists($field->inname, $_POST)) {
                    $vModel = new FormValue;
                    $vModel->form_id = $form_id;
                    $vModel->run_id = $run_id;
                    $vModel->field_name = $field->inname;
                    $vModel->field_id = $field->field_id;
                    $vModel->value = $_POST[$field->inname];
                    $vModel->save();
                    unset($vModel);
                }
            }
            //建立流程第一步.
            $prc_head = new FlowRunPrc;
            $prc_head->run_id = $run_id;
            $prc_head->step_name = $step_head->step_name;
            $prc_head->step_user = Yii::app()->session["userInfo"]["name"];
            $prc_head->step_no = $step_head->step_no;
            $prc_head->start_time = $this->_now;
            $prc_head->handel_time = '';
            $prc_head->save();
            
            $transaction->commit();
            //出发流程转交动作,将流程转交到第二步
            $this->transmit($flow_id, $run_id, $step_head->step_no, $type = "next", $form_data = array(
                'remark' => ''
            ));
            return $run_id;
        }
        catch(Exception $e) {
            $transaction->rollBack();
            throw new Exception("Error Processing Request", 1);
        }
    }
    /**
     *获取所有流程步骤,及步骤No,step_id数组
     */
    public function getFlowStep($flow_id = '') {
        // 读取flow_run表中,对应run_id数据的state,判断流程步骤
        // 根据对应流程的步骤号,来获得下一步骤
        $allstep = FlowStep::model()->findAll(array(
            // "condition"=> 'flow_id=:flow_id and step_no > :step_no',
            "condition" => 'flow_id=:flow_id',
            "params" => array(
                'flow_id' => $flow_id
            ) ,
            "order" => "step_no asc",
        ));
        foreach ($allstep as $step) {
            $this->_allStepNo[] = $step->step_no;
        }
        foreach ($allstep as $step) {
            $this->_allStepId[] = $step->step_id;
        }
        return $allstep;
    }
    /**
     * 获取当前步骤之后的所有步骤条件
     * @param  [type] $step_id [description]
     * @return [type]          [description]
     */
    public function getAfterCon($step_id) {
        $afterConStepid = array();
        $criteria = new CDbCriteria;
        $criteria->addCondition('handel_time>0');
        $criteria->addInCondition('step_id', $this->_allStepId);
        $criteria->condition = 'step_id>:step_id';
        $criteria->params = array(
            ':step_id' => $step_id
        );
        $criteria->order = 'step_id asc';
        $afterConModels = FlowCond::model()->findAll($criteria);
        foreach ($afterConModels as $key => $conModel) {
            $afterConStepid[] = $conModel->step_id;
        }
        return $afterConStepid;
    }
    /**
     * 对应流程的所有进程
     * @param  string $run_id [description]
     * @return [type]         [description]
     */
    public function getPrePrc($run_id = '') {
        $allPrc = FlowRunPrc::model()->findAll(array(
            "condition" => "run_id = :run_id",
            "params" => array(
                ":run_id" => $run_id
            ) ,
            "order" => "prc_id desc",
        ));
        $prePrc = $allPrc[1];
        return $prePrc;
    }
    /**
     *转交下一步,或回退流程
     *修改流程运行信息,更新流程步骤
     * @return [string]     ['next','up']
     */
    public function transmit($flow_id = "", $run_id = "", $step_no = "", $type = "next", $form_data = array()) {
        if (empty($run_id) || empty($step_no)) return false;
        //获取流程的所有步骤
        $allStep = $this->getFlowStep($flow_id);
        $cur_index = array_search($step_no, $this->_allStepNo);
        if ($type == "next") {
            //判断流程步骤中是否有下一步或上一步骤
            $step = isset($allStep[$cur_index + 1]) ? $allStep[$cur_index + 1] : null;
            $conModel = new Condition;
            $conditionPass = $conModel->runDecide($step);
            // 判断是否符合顺序执行一下步条件
            if (!$conditionPass) {
                $step = null;
                $step_id = $this->_allStepId[$cur_index];
                $afterStepid = array_slice($this->_allStepId, $cur_index + 1); //当前步骤之后的所有步骤id Array
                $conStepid = $this->getAfterCon($step_id); //当前步骤之后的所有有条件的步骤 id Array
                $noConStep = array_diff($afterStepid, $conStepid); // 求差集,得出无条件步骤id Array
                $noConStep = array_values($noConStep);
                foreach ($conStepid as $stepid) {
                    $index = array_search($stepid, $this->_allStepId);
                    $t_step = $allStep[$index];
                    if ($conModel->runDecide($t_step)) {
                        $step = $t_step;
                        break;
                    }
                }
                if (is_null($step)) {
                    $step_id = isset($noConStep[0]) ? $noConStep[0] : null; //得出区域结束点step_id
                    $next_index = array_search($step_id, $this->_allStepId);
                    $step = $next_index ? $allStep[$next_index] : null; //
                    
                    
                }
            }
        } else {
            //获取当前进程的前一个进程(即为回退流程)
            $prePrc = $this->getPrePrc($run_id);
            $pre_StepNo = $prePrc->step_no;
            $pre_index = array_search($pre_StepNo, $this->_allStepNo);
            $step = isset($allStep[$pre_index]) ? $allStep[$pre_index] : null;
            $step->step_user = $prePrc->step_user; //在流程的第一个进程中,step_user默认为一个值,所以所以需要动态修改
            
            
        }
        if (!$step && $type == "next") /*流程没有下一步骤.结束流程*/ {
            return $this->finish($flow_id, $run_id, $step_no, $form_data);
        } else
        /*有下一步骤,转交步骤*/ {
            if (!$step) {
                throw new Exception("错误,不能正确获取步骤相关信息", 1);
            }
            // if(!$conditionPass)
            //  return false;
            //更新步骤的处理时间
            FlowRunPrc::model()->updateAll(array(
                'handel_time' => $this->_now,
                'remark' => $form_data['remark']
            ) , 'run_id=:run_id and step_no=:step_no', array(
                ':run_id' => $run_id,
                ':step_no' => $step_no
            ));
            //修改进程表,增加一个进程.
            $new_prc = new FlowRunPrc;
            $new_prc->step_name = $step->step_name;
            $new_prc->step_no = $step->step_no;
            $new_prc->start_time = $this->_now;
            $new_prc->step_user = $step->step_user;
            $new_prc->timeout = $this->_now + $step->timeout * 3600 * 24;
            $new_prc->run_id = $run_id;
            
            if ($new_prc->save()) {
                $runModel = FlowRun::model()->findByPk($run_id);
                $runModel->step_no = $step->step_no;
                $runModel->save();
                return true;
            } else return false;
        }
    }
    /**
     * 用于结束流程
     */
    public function finish($flow_id = "", $run_id = "", $step_no = "", $form_data = array()) {
        //修改运行表中的状态,并将进程表中删除进程.
        //修改flow_run表中,对应run_id数据的state
        FlowRunPrc::model()->updateAll(array(
            'handel_time' => time() ,
            'remark' => $form_data['remark']
        ) , 'run_id=:run_id and step_no=:step_no and handel_time = \'\'', array(
            ':run_id' => $run_id,
            ':step_no' => $step_no
        ));
        
        $flow_run = FlowRun::model()->findByPk($run_id);
        $flow_run->run_state = 1; //1为以完成
        $flow_run->end_time = time();
        if ($flow_run->save()) return true;
        else return false;
    }
    /**
     *用与发送提醒邮件
     */
    public function remind() {
    }
}
?>