<?php
class ModulesController extends JController {
    public $defaultAction = 'index';
    public function init() {
        parent::init();
        $this->modelName = 'Modules';
    }
    
    public function actionIndex() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes();
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }
}
