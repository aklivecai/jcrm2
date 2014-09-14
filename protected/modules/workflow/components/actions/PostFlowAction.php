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
        $fUtils = new FlowUtils();
        $fUtils->onErrors = function ($data) {
            if (false && $this && isset($this['controller'])) {
                $this->controller->message($data, false);
            } else {
                Yii::app()->getController()->message($data, false);
            }
        };
        $fUtils->onSuccess = function ($data) {
            if (false && $this && isset($this['controller'])) {
                $this->controller->message($data, false);
            } else {
                Yii::app()->getController()->message(Yii::app()->getController()->createUrl('index') , true);
            }
        };
        $data = $fUtils->createFlow(Tak::getSId($id));
        $controller->setLayoutWin();
        $data+= array(
            'id' => $id,
        );
        if ($controller->isAjax && count($_POST) > 0) {
            // $fUtils->raiseEvent('onErrors', $this);
            $fUtils->createFlowData($_POST);
            return false;
        }
        $controller->render("flowinfo", $data);
    }
}
