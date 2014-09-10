<?php
class DefaultController extends WorkflowController {
    public function actions() {
        return array(
            // 流程申请表单填写页面
            'postflow' => 'wf.components.actions.PostFlowAction',
        );
    }
    public $label = array(
        "提交流程",
        "转交流程",
        "结束流程"
    );
    /**
     * 流程列表页面中,流程操作按钮的设置
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function writeButtons($data) {
        // print_r($data);
        $tags = array(
            'view' => CHtml::link('流程查看', Yii::app()->controller->createUrl("FlowDetail", array(
                "run_id" => $data["run_id"]
            ))) ,
        );
        $run_prc = $data->run_prc[0];
        // if ($_GET['type']!='handle'&&( $data["step_no"] > 0 || $data["start_time"] != $run_prc->start_time)) {
        if ($_GET['type'] != 'handle') {
            $tags[] = CHtml::link('处理流程', $this->createUrl("flowinfo", array(
                "run_id" => $data["run_id"],
                "step_no" => $data["step_no"],
                "flow_id" => $data["flow_id"]
            )));
        }
        echo join(' | ', $tags);
    }
    /**
     * 已经处理过的
     * @return [type] [description]
     */
    public function actionHandle() {
        $flowrun = new FlowRun('search'); //定义查询模型
        $flowinfos = FlowInfo::model()->findAll();
        $userInfo = Yii::app()->session->get('userInfo');
        $gridDataProvider = $flowrun->searchBy($userInfo["name"]);
        $this->render('list_handle', array(
            'gridDataProvider' => $gridDataProvider,
        ));
    }
    /**
     * 已经完成的审批
     * @return [type] [description]
     */
    public function actionCompleted() {
        $flowrun = new FlowRun('search'); //定义查询模型
        $flowrun->run_state = 1;
        $flowrun->begin_user = $userInfo["name"];
        $gridDataProvider = $flowrun->search();
        $this->render('list_completed', array(
            'gridDataProvider' => $gridDataProvider
        ));
    }
    /**
     * 需要我审批的
     * @return [type] [description]
     */
    public function actionMyNeet() {
        $flowrun = new FlowRun('search'); //定义查询模型
        $flowrun->run_state = 0;
        $flowrun->run_prc = array(
            'handel_time' => '0',
            'step_user' => $userInfo["name"]
        );
        $gridDataProvider = $flowrun->search();
        $this->render('list_myneet', array(
            'gridDataProvider' => $gridDataProvider,
        ));
    }
    public function actionIndex() {
        // $this->setPageTitle(Tk::g('My Workflow'));
        $flowrun = new FlowRun('search'); //定义查询模型
        //我的
        $flowrun->begin_user = 0;
        // 未完成
        $flowrun->run_state = 0;
        $gridDataProvider = $flowrun->search();
        $this->render('list_my', array(
            'gridDataProvider' => $gridDataProvider,
        ));
    }
    /**
     * 显示流程的详流程
     * @return [type] [description]
     */
    public function actionFlowDetail($run_id = "") {
        $runModel = FlowRun::model()->with(array(
            'run_prc' => array(
                'order' => 'prc_id desc'
            ) ,
            'prc_data' => array() ,
        ))->findByPk($run_id);
        // var_dump($runModel->run_prc);
        $users = Tool::userList();
        $this->render("flowdetail", array(
            'model' => $runModel,
            'users' => $users
        ));
    }
    /**
     * 流程申请表单填写页面
     * [actionFlowInfo description]
     * @param  string $run_id  [description]
     * @param  string $step_no [description]
     * @return [type]          [description]
     */
    public function actionFlowInfo($id) {
        //获取流程信息
        $flowInfo = $this->loadModels($id, 'FlowInfo');
        //获取流程表单信息
        $fowForm = $this->loadModels($flowInfo->primaryKey, 'FormInfo');
        $this->setLayoutWin();
        $model = new FormModel('create');
        //获取第一个步骤
        $step = $flowInfo->getFirstStep();
        if ($step == null) {
            $this->error();
        }
        $this->render("flowinfo", array(
            'flowInfo' => $flowInfo,
            'fowForm' => $fowForm,
            'model' => $model,
            'step' => $step,
            'id' => $id,
        ));
    }
    /**
     * 表单流程的提交
     * @return
     */
    public function actionPostFlowInfo() {
        $flow_id = $_POST["flow_id"];
        $run_id = $_POST["run_id"];
        $step_no = $_POST["step_no"];
        $label = $_POST["label"];
        if (!empty($run_id) && !empty($step_no)) {
            $type = isset($_POST["type"]) ? $_POST["type"] : 0;
            if ($type == 0) $this->TurnNext($flow_id, $run_id, $step_no);
            else $this->FlowBack($flow_id, $run_id, $step_no);
            $this->redirect(array(
                'flowList'
            ));
        }
        $userInfo = Yii::app()->session->get('userInfo');
        $model = new FormModel;
        $process = new Process;
        $model->attributes = $_POST['FormModel'];
        $model->user = $userInfo['name'];
        if (!$model->validate()) {
            $form_id = FlowInfo::model()->findByPk($flow_id)->form_id;
            $form = FormInfo::model()->findByPk($form_id);
            $fields = FormField::model()->findAll('form_id=:form_id', array(
                ':form_id' => $form_id
            ));
            $formProduce = new FormProducer;
            $formProduce->pushField($fields);
            $formProduce->pushValue($_POST);
            $html = $formProduce->buildForm($form);
            
            $this->render("flowinfo", array(
                "model" => $model,
                "label" => $this->label[$label],
                "flow_id" => $flow_id,
                "label_index" => $label,
                "run_id" => $run_id,
                "step_no" => $step_no,
                'html' => $html
            ));
            Yii::app()->end();
        }
        //将表单数据插入数据库中,并创建流程信息()
        $run_id = $process->creatFlow($model, $flow_id);
        $model->run_id = $run_id;
        $model->save();
        $this->redirect(array(
            'Index'
        ));
    }
    /**
     * 用于展示列表,
     * @return [type] [description]
     * 已弃用
     */
    public function actionFlowList() {
        $gridDataProvider = new CActiveDataProvider('FlowRun', array(
            'criteria' => array(
                // 'condition'=> 'run_state = 0',
                'with' => array(
                    'run_prc'
                ) ,
            ) ,
        ));
        // var_dump($gridDataProvider->getData());
        $this->render('flowlist', array(
            'gridDataProvider' => $gridDataProvider
        ));
    }
    /**
     * 根据设置,转交或结束流程
     * @param  string $flow_id [description]
     * @param  string $run_id  [description]
     * @param  string $step_no [description]
     * @return [void]          [description]
     */
    public function TurnNext($flow_id = '', $run_id = '', $step_no = '') {
        if (empty($flow_id) || empty($run_id) || empty($step_no)) {
            $this->redirect("flowlist");
        }
        //获取流程状态,及转交条件
        $process = new Process;
        $form_data = array(
            'remark' => $_POST['remark']
        ); //表单提交数据
        if ($process->transmit($flow_id, $run_id, $step_no, 'next', $form_data)) {
            $this->redirect("Index");
        }
    }
    /**
     * 回退流程
     * @param  string $flow_id [description]
     * @param  string $run_id  [description]
     * @param  string $step_no [description]
     * @return [type]          [description]
     */
    public function FlowBack($flow_id = '', $run_id = '', $step_no = '') {
        $process = new Process;
        $form_data = array(
            'remark' => $_POST["remark"]
        ); //表单提交数据
        $process->transmit($flow_id, $run_id, $step_no, 'up', $form_data);
        $this->redirect(array(
            'Index'
        ));
    }
    /*
     * 设置流程状态的显示字符
    */
    public function flowState($data, $row) {
        if (empty($data->run_prc[0]->handel_time) && $data->run_prc[0]->timeout < time()) return '已超时';
        else return '正常进行中';
    }
    /**
     * 获取当前用户正在处理流程数量,用户提醒用户
     * @return [type] [description]
     */
    public function actiongetRunNum() {
        $userInfo = Yii::app()->session->get('userInfo');
        $count = FlowRunPrc::model()->count('step_user=:step_user and handel_time = 0 ', array(
            ':step_user' => $userInfo['name']
        ));
        $json = array(
            'num' => $count
        );
        echo json_encode($json);
    }
    /**
     * Ajax改变用户session
     *
     */
    public function actionChangeUser() {
        $user = $_POST['user'];
        $allUser = Tool::userList();
        $userInfo['label'] = $allUser[$user];
        $userInfo['name'] = $user;
        Yii::app()->session->add('userInfo', $userInfo);
    }
}
