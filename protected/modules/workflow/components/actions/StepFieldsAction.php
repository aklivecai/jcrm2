<?php
/**
 *步骤中的控件
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 12:09:08
 * @version $Id$
 *
 */

class StepFieldsAction extends CAction {
    private $model = null;
    private $itemid = null;
    private $modelName = 'StepFields';
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
        $types = Tool::getFieldTypes();
        
        $this->itemid = $model->primaryKey;
        $this->controller->setLayoutWin();
        if ($this->controller->isAjax) {
            $m = $this->modelName;
            $_model = new $m();
            $list = Tak::getPost($m);
            $values = array();
            if ($list && count($list) > 0) {
                foreach ($list as $key => $value) {
                    $ikey = Tak::getSId($key);
                    if (isset($files[$ikey])) {
                        $_m = new $m('create');
                        $_m->attributes = $value;
                        $_m->field_id = $ikey;
                        $_m->step_id = $modelStep->primaryKey;
                        $_m->hide = $_m->show == 1 ? 0 : 1;
                        if ($_m->validate()) {
                            $values[] = $_m;
                        }
                    }
                }
                $status = true;
                if (count($values) > 0) {
                    //清空之前的字段数据
                    $modelStep->delFields();
                    foreach ($values as $key => $value) {
                        $value->save();
                    }
                    $msg = '保存成功';
                    unset($values);
                } else {
                    $msg = '没有需要保存的数据';
                    $status = false;
                }
                $this->message($msg, $status);
            }
            exit();
        }
        $list = $modelStep->getFieldsBySql();
        $_files = array();
        $defaultVal = StepFields::model()->attributes;
        unset($defaultVal['id']);
        unset($defaultVal['step_id']);
        unset($defaultVal['field_id']);
        unset($defaultVal['sfrom']);
        // Tak::KD($defaultVal);
        foreach ($files as $key => $value) {
            $v = array(
                'id' => Tak::setSId($key) ,
                'name' => $value['field_name'],
                'type' => $types[$value['otype']],
            );
            $foll = null;
            if (isset($list[$key])) {
                unset($list[$key]['field_id']);
                $foll = $list[$key];
            } else {
                $foll = $defaultVal;
            }
            $v = array_merge($foll, $v);
            $_files[] = $v;
        }
        // Tak::KD($_files);
        $this->controller->render('stepfields', array(
            'model' => $modelStep,
            'files' => $_files,
            'id' => $id,
            'itemid' => $itemid,
        ));
    }
    private function message($info, $status = true) {
        $this->controller->message($info, $status);
    }
}
