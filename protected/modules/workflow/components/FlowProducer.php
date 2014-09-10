<?php
/**
 * 流程生成者
 */
class FlowProducer {
    public function __construct() {
    }
    /*
     * 批量设置流程步骤,已弃用
    */
    public function setStep(array $stepArr, $flow_id) {
        $db_con = Yii::app()->db;
        $transaction = $db_con->beginTransaction();
        try {
            $step_no = 1;
            $model = new FlowStep;
            $model->step_user = 'admin';
            $model->step_name = '流程发起人';
            $model->step_no = - 1;
            $model->flow_id = $flow_id;
            
            $model->save();
            foreach ($stepArr as $step) {
                $model = new FlowStep;
                $model->attributes = $step;
                $model->step_no = $step_no;
                $model->flow_id = $flow_id;
                $model->save();
                unset($model);
                $step_no++;
            }
            $transaction->commit();
        }
        catch(Exception $e) {
            $transaction->rollback();
        }
        return true;
    }
}
?>