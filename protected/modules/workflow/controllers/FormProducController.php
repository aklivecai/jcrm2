<?php
/**
 * 用于生成表单字段
 */

class FormProducController extends WorkflowController {
    /**
     * 表单列表展示
     * 已没有该目录
     *
     */
    public function actionIndex() {
        $gridDataProvider = new CActiveDataProvider('FormInfo', array());
        $this->render('index', array(
            'gridDataProvider' => $gridDataProvider
        ));
    }
    /*
     * 字段删除
    */
    public function actionDeleteField() {
        $json = array(
            'error' => 1
        );
        $field_id = $_POST['field_id'];
        if (FormField::model()->exists('field_id=:field_id', array(
            ':field_id' => $field_id
        ))) {
            if (FormField::model()->deleteByPk($field_id)) {
                $json['error'] = 0;
            }
        }
        $json = json_encode($json);
        echo $json;
    }
    /**
     * 表单创建及编辑
     */
    public function actionCreate() {
        $fieldModel = new FormField;
        if (Yii::app()->request->isPostRequest) {
            // $formModel->attributes = $_POST['FormInfo'];
            // // var_dump($_POST['FormField']);
            // if($formModel->validate() && $formModel->save())
            // {
            $form_id = $_POST['form_id'];
            $produce = new FormProducer;
            if ($produce->insertField($_POST['FormField'], $form_id)) {
                $this->redirect(Yii::app()->request->urlReferrer);
            }
            //  Yii::app()->user->setFlash('error','表单字段设置错误!');
            // }
            
            
        }
        $this->redirect(Yii::app()->request->urlReferrer);
        // $this->render('infoform', array('fieldModel'=>$fieldModel));//, 'formModel'=>$formModel));
        
        
    }
    /**
     * 表单预览页面，也未开放
     */
    public function actionDetail($form_id = '') {
        //获取表单和表单字段信息
        $form = FormInfo::model()->findByPk($form_id);
        $fields = FormField::model()->findAll('form_id=:form_id', array(
            ':form_id' => $form_id
        ));
        //解析表单字段,构建表单html代码
        $formProduce = new FormProducer;
        $formProduce->pushField($fields);
        $html = $formProduce->buildForm($form);
        $this->render('detail', array(
            'html' => $html
        ));
    }
}
