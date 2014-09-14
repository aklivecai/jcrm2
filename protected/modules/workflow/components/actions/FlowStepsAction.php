<?php
/**
 *流程步步骤定义
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 12:09:08
 * @version $Id$
 *
 */

class FlowStepsAction extends CAction {
    private $model = null;
    private $itemid = null;
    private $modelName = 'FlowStep';
    public function run($id) {
        $model = $this->controller->loadModel($id);
        $this->itemid = $model->primaryKey;
        $action = Tak::getQuery('ajax', '');
        $this->controller->setLayoutWin();
        switch ($action) {
            case 'del':
                $this->del();
            break;
            case 'action':
                $this->action();
            break;
            default:
                $this->message('非法操作', fasle);
            break;
        }
        exit;
    }
    private function message($info, $status = true) {
        $this->controller->message($info, $status);
    }
    private function loadModel($id, $err = false) {
        $model = $this->controller->loadModels($id, $this->modelName, false, $err);
        if ($err && $model->flow_id != $this->itemid) {
            $this->message('不存在的步骤', false);
        }
        return $model;
    }
    //删除步骤
    private function del() {
        $id = Tak::getPost('itemid');
        $model = $this->loadModel($id);
        // Tak::KD($model->attributes, 1);
        if ($model->step_no == 1) {
            $this->message('第一步不允许删除', false);
        } else {
            $model->delete();
            $this->message('1');
        }
    }
    public function action() {
        $id = Tak::getPost('itemid', 0);
        $model = $this->loadModel($id, false);
        $m = $this->modelName;
        if ($model == null) {
            $model = new $m('create');
        }
        $model->attributes = Tak::getPost($m);
        $model->flow_id = $this->itemid;
        //解码传递过来的处理人编号
        if ($model->step_user) {
            $model->step_user = Tak::getSId($model->step_user);
        }
        if ($model->validate()) {
            $model->save();
            $this->message($model->getSId() , true);
        } else {
            $error = Tak::getMsgByErrors($model->getErrors());
            $this->message($error, false);
        }
    }
}
