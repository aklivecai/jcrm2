<?php
class ProductionController extends WorkflowController {
    public $modelName = 'FlowInfo';
    public function actions() {
        return array(
            // 表单设计
            'settingform' => 'wf.components.actions.SettingFormAction',
            // 流程步步骤定义
            'flowsteps' => 'wf.components.actions.FlowStepsAction',
            //步骤条件
            'stepcondition' => 'wf.components.actions.StepConditionAction',
            //步骤字段
            'stepfields' => 'wf.components.actions.StepFieldsAction',
        );
    }
    public function getLink($id, $status) {
        $htmls = array();
        $htmls[] = JHtml::link(Tk::g("设计表单") , array(
            "SettingForm",
            "id" => $id
        ) , array(
            "class" => "target-win",
            "data-full" => "true"
        ));
        $htmls[] = JHtml::link(Tk::g(array(
            "Setting",
            "Step"
        )) , array(
            "steps",
            "id" => $id
        ) , array(
            "class" => "target-win",
            'data-width' => 650,
            'data-height' => 400,
        ));
        $htmls[] = JHtml::link(TakType::item('workstatus', $status == 1 ? 2 : 1) , array(
            "ToggleStatus",
            "id" => $id
        ));
        $htmls[] = JHtml::link("修改", array(
            "action",
            "id" => $id
        ) , array(
            "class" => "target-win",
            "data-width" => 320,
            "data-height" => 200
        ));
        $htmls[] = JHtml::link("删除", array(
            "delete",
            "id" => $id
        ) , array(
            "class" => "revoke-link"
        ));
        
        $htmls[] = JHtml::link(Tk::g(array(
            "Setting",
            "Step"
        )) , 'http://a.cn/_/project/javascript/mxgraph/i.html', array(
            "class" => "target-win",
            "data-full" => "true"
        ));
        return implode(' | ', $htmls);
    }
    /**
     * 设置流程中，流程展示列表
     * @return [type] [description]
     */
    public function actionIndex() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }
    /**
     * 删除流程
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        $this->redirect(array(
            'index'
        ));
    }
    /**
     * 更改流程状态
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionToggleStatus($id) {
        $model = $this->loadModel($id);
        $model->status = $model->status == 1 ? 2 : 1;
        $model->save();
        $this->redirect(array(
            'index'
        ));
    }
    /**
     * 流程步骤列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionSteps($id) {
        $model = $this->loadModel($id);
        $this->setLayoutWin();
        /*
        $m = new FlowStep('create');
        $m->attributes = array(
            'step_user' => 50,
            'step_name' => '申请',
            'step_no' => 50,
            'flow_id' => 12,
        );
        Tak::KD($m->attributes);
        $m->validate();
        Tak::KD($m->getErrors());
        */
        
        $this->render('steps', array(
            'id' => $id,
            'model' => $model,
        ));
    }
    public function actionAction($id = 0) {
        $m = $this->modelName;
        if ($id) {
            $model = $this->loadModel($id);
        } else {
            $model = new $m('create');
        }
        $this->setLayoutWin();
        if ($model->isNewRecord) {
            $template = 'create';
            $info = '保存成功';
        } else {
            $template = 'update';
            $info = '修改成功';
        }
        if (isset($_POST[$m])) {
            $this->performAjaxValidation($model);
            $model->attributes = $_POST[$m];
            if ($model->validate()) {
                $model->save();
                $script = sprintf('closeWin({url:true,info:"%s"});', $info);
                $this->render('//chip/iframe', array(
                    'model' => $model,
                    'script' => $script,
                ));
                exit;
            }
        }
        $this->render($template, array(
            'model' => $model,
            'id' => $id
        ));
    }
}
?>