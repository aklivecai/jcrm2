<?php
/**
 *流程创建
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-10 14:21:05
 * @version $Id$
 */

class PostFlowAction extends CAction {
    private $model = null;
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
                    if (count($_fields)>0) {
                        $ids = array_keys($_fields);
                        $idsStr = 
                        $_fields = $_POST['fields'];                        
                        $writeFiles = array();
                        foreach ($_fields as $key => $value) {

                        }
                        //所有字段
                        $files = $fowForm->getFields();
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
