<?php
class WorkflowModule extends CWebModule {
    public $appLayout = 'application.views.layouts.main';
    public $layout = 'workflow.views.layouts.main';
    public function init() {
        Yii::app()->defaultController = 'default';
        $this->setImport(array(
            'workflow.models.*',
            'workflow.components.*',
        ));
    }
    
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            return true;
        } else return false;
    }
}
