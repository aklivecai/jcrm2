<?php
/**
 *表单步骤条件
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 12:09:08
 * @version $Id$
 *
 */

class StepConditionAction extends CAction {
    private $model = null;
    private $itemid = null;
    private $modelName = 'StepCondition';
    /**
     * 步骤条件
     * @param  int $id     流程编号
     * @param  int $itemid 步骤编号
     * @return [type]         [description]
     */
    public function run($id, $itemid) {
        //获取表单字段,表单和流程编号一样
        $model = $this->controller->loadModels($id, 'FormInfo', fasle, true);
        $modelStep = $this->controller->loadModels($itemid, 'FlowStep', fasle, true);
        if ($modelStep->flow_id != $model->primaryKey) {
            $this->controller->msgTip('不存在的步骤');
        }
        $files = $model->getFieldsBySql(0, true);
        //没有字段不执行
        if (count($files) == 0) {
            $this->controller->msgTip('没有字段不执行');
        }
        //条件列表
        $list = $modelStep->getConditionsBySql();
        $types = Tool::getConditionType();
        
        $this->itemid = $model->primaryKey;
        $this->controller->setLayoutWin();
        $action = Tak::getQuery('ajax', false);
        if ($this->controller->isAjax && $action) {
            $m = $this->modelName;
            $_model = new $m();
            switch ($action) {
                case 'del':
                    $_ids = Tak::getPost('ids');
                    $ids = array();
                    foreach ($_ids as $key => $value) {
                        $_id = Tak::getSId($value);
                        if (isset($list[$_id])) {
                            $ids[] = $_id;
                        }
                    }
                    if (count($ids) > 0) {
                        // Tak::KD($ids,1);
                        // Tak::KD(sprintf('con_id IN(%s)', implode(',', $ids)),1);
                        $sql = sprintf(' step_id=%s AND  con_id IN (%s) ', $modelStep->primaryKey, implode(',', $ids));
                        $_model->deleteAll($sql);
                        $modelStep->upConditions();
                    }
                    $this->message('');
                break;
                case 'add':
                    $_model->attributes = Tak::getPost($m);
                    $_model->step_id = $modelStep->primaryKey;
                    $_model->field_id = Tak::getSId($_model->field_id);
                    if ($_model->validate()) {
                        if (!isset($files[$_model->field_id])) {
                            $_model->addError('field_id', '字段输入不正确');
                        }
                        if (!isset($types[$_model->type])) {
                            $_model->addError('type', '条件输入不正确');
                        }
                    }
                    $errors = $_model->getErrors();
                    $msg = '';
                    $status = true;
                    if (count($errors) == 0) {
                        $_model->html = sprintf('[%s]  %s  %s', $files[$_model->field_id]['field_name'], $types[$_model->type], $_model->value);
                        $_model->save();
                        $msg = $_model->primaryKey;
                        $modelStep->upConditions();
                    } else {
                        $msg = Tak::getMsgByErrors($errors);
                        $status = false;
                    }
                    $this->message($msg, $status);
                break;
                default:
                break;
            }
        }
        $_list = array();
        foreach ($list as $key => $value) {
            $_list[] = array(
                'id' => Tak::setSId($key) ,
                'value' => $value['html']
            );
        }
        $_files = array();
        foreach ($files as $key => $value) {
            $_files[] = array(
                'id' => Tak::setSId($key) ,
                'value' => $value['field_name']
            );
        }
        $_types = array();
        foreach ($types as $key => $value) {
            $_types[] = array(
                'id' => $key,
                'value' => $value
            );
        }
        $this->controller->render('condition', array(
            'model' => $modelStep,
            'files' => $_files,
            'list' => $_list,
            'types' => $_types,
            'id' => $id,
            'itemid' => $itemid,
        ));
    }
    private function message($info, $status = true) {
        $this->controller->message($info, $status);
    }
}
