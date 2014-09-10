<?php
/**
 *流程创建
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-10 14:21:05
 * @version $Id$
 */

class PostFlowAction extends CAction {
    private $model = null;
    private $separator = '$$';
    public function run($id) {
        $controller = $this->controller;
        //获取流程信息
        $flowInfo = $controller->loadModels($id, 'FlowInfo');
        //获取流程表单信息
        $fowForm = $controller->loadModels($flowInfo->primaryKey, 'FormInfo');
        $m = 'FormModel';
        $model = new $m('create');
        //获取第一个步骤
        $step = $flowInfo->getFirstStep();
        if ($step == null) {
            $controller->error();
        }
        $controller->setLayoutWin();
        if ($controller->isAjax) {
            if (isset($_POST[$m])) {
                $model->attributes = $_POST[$m];
                if ($model->validate()) {
                    // $model->save();
                    //当前步骤需要可以输入的字段
                    $files_attr = $step->getWFields();
                    //
                    if (count($files_attr) > 0) {
                        $errors = array();
                        $ids = array_keys($files_attr);
                        $idsStr = sprintf('%s%s%s', $this->separator, implode($this->separator, $ids) , $this->separator);
                        $_fields = $_POST['fields'];
                        $writeFiles = array();
                        //获取需要填写POST过来的字段数据
                        foreach ($_fields as $key => $value) {
                            $__id = Tak::getSId($key);
                            if (strpos($idsStr, $__id . $this->separator)) {
                                $writeFiles[$__id] = $value;
                            }
                        }
                        Tak::KD($writeFiles);
                        //需要录入的字段对象
                        $files = $fowForm->getFields($ids);
                        foreach ($files as $value) {
                            if (isset($writeFiles[$value->primaryKey])) {
                                //后期需要进行值的过滤,如select,radio,checkbox 只能选择已有的内容
                                
                            } else {
                                $errors[] = sprintf('[%s] 必需填写不能为空', $value->field_name);
                            }
                        }
                    }
                    Tak::KD($files_attr);
                    $status = true;
                    $info = '保存成功';
                } else {
                    $info = Tak::getMsgByErrors($model->getErrors());
                }
            }
            $controller->message($info, $status);
            exit;
        }
        
        $controller->render("flowinfo", array(
            'flowInfo' => $flowInfo,
            'fowForm' => $fowForm,
            'model' => $model,
            'step' => $step,
            'id' => $id,
        ));
    }
}
