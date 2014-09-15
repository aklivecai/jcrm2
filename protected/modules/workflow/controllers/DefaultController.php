<?php
class DefaultController extends WorkflowController {
    public $modelName = 'FlowRun';
    private $userid = 0;
    public function init() {
        parent::init();
        // $this->userid = Tak::getManageid();
        $this->userid = Tak::getCid();
    }
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
    
    public function getLink($id) {
        $id = $this->setSId($id);
        $htmls = array();
        $htmls[] = JHtml::link('查看', $this->createUrl('view', array(
            'id' => $id
        )) , array(
            'class' => 'target-win',
            'data-full' => true,
        ));
        switch ($this->getAction()->id) {
            case 'index':
                /*
                $htmls[] = JHtml::link('撤销', $this->createUrl('CancelFlow', array(
                    'id' => $id,
                    'action' => 'update'
                )) , array(
                    'class' => 'target-win',
                    'data-full' => true,
                ));
                */
            break;
            case 'myneet':
                $htmls[] = JHtml::link('处理', $this->createUrl('HandleRun', array(
                    'id' => $id,
                    'action' => 'update'
                )) , array(
                    'class' => 'target-win',
                    'data-full' => true,
                ));
            break;
            default:
            break;
        }
        echo implode(' / ', $htmls);
    }
    /**
     * 浏览流程详细(权限后期过滤,只有自己可以浏览,处理过的人可以浏览,有权限的)
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $fUtils = new FlowUtils();
        $fUtils->onErrors = function ($data) {
            if (false && $this && isset($this['controller'])) {
                $this->controller->message($data, false);
            } else {
                Yii::app()->getController()->message($data, false);
            }
        };
        $data = $fUtils->getViewFlow($model->primaryKey);
        $data['id'] = $id;
        $this->setLayoutWin();
        $this->render($this->templates['view'], $data);
    }
    
    public $acitonUrl = null;
    public function actionHandleRun($id) {
        $model = $this->loadModel($id);
        if ($model->run_state == 1) {
            $this->message('流程已经结束', false);
        } elseif ($model->cuser_id != $this->userid) {
            //判断处理流程的身份是不是自己
            $this->message('当前流程不是你处理的', false);
        }
        $fUtils = new FlowUtils();
        $data = $fUtils->getViewFlow($model->primaryKey);
        $fUtils->onErrors = function ($data) {
            Yii::app()->getController()->message($data, false);
        };
        $fUtils->onSuccess = function ($data) {
            Yii::app()->getController()->message(Yii::app()->getController()->acitonUrl, true);
        };
        if ($this->isAjax && count($_POST) > 0) {
            $type = Tak::getPost('type', 1);
            $note = Tak::getPost('note', false);
            
            if ($data['stepInfo']->isFirst()) {
                $type = 1;
                $this->acitonUrl = $this->createUrl('index');
            } else {
                $this->acitonUrl = $this->createUrl('handle');
                if ($type != 1) {
                    $type = 0;
                }
            }
            //退回重新申请的,需要填写理由
            if (!$note && ($type == 0 || $data['stepInfo']->isFirst())) {
                $data = '请输入您的办理理由!';
                $this->message($data, false);
                exit;
            }
            $fUtils->HandleRun($type, $note, $_POST);
            return false;
        }
        $data['id'] = $id;
        $this->setLayoutWin();
        $this->render('handle', $data);
    }
    /**
     * 查看流程的步骤列表
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionViewRun($id) {
        $model = $this->loadModel($id);
        $this->setLayoutWin();
        $this->render('run_prc', array(
            'model' => $model,
            'id'=>$id
        ));
    }
    
    public function actionIndex() {
        $model = new FlowRun('search'); //定义查询模型
        //我的
        $model->user = $this->userid;
        // 未完成
        $model->run_state = 0;
        $this->render('list_my', array(
            'model' => $model,
        ));
    }
    /**
     * 需要我审批的
     * @return [type] [description]
     */
    public function actionMyNeet() {
        
        $model = new FlowRun('search'); //定义查询模型
        //我的
        $model->run_state = 0;
        //我的
        $model->cuser_id = $this->userid;
        $this->render('list_myneet', array(
            'model' => $model,
        ));
    }
    /**
     * 已经处理过的
     * @return [type] [description]
     */
    public function actionHandle() {
        $model = new FlowRun('search'); //定义查询模型
        $model->suid = $this->userid;
        $this->render('list_handle', array(
            'model' => $model,
        ));
    }
    /**
     * 已经完成的审批
     * @return [type] [description]
     */
    public function actionCompleted() {
        $model = new FlowRun('search'); //定义查询模型
        //我的
        $model->run_state = 1;
        //我的
        $model->user = $this->userid;
        $this->render('list_completed', array(
            'model' => $model,
        ));
    }
    /**
     * 获取当前用户正在处理流程数量,用户提醒用户
     * @return [type] [description]
     */
    public function actionGetRunNum() {
        $count = FlowRunPrc::model()->getUcount($this->userid);
        $this->message($count, $count > 0);
    }
    /**
     * Ajax改变用户session
     *
     */
    public function actionChangeUser($id) {
        Tak::setCid($id);
        $this->message('', true, true);
    }
}
