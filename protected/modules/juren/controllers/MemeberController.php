<?php
class MemeberController extends JController {
    public $defaultAction = 'index';
    public function init() {
        $this->modelName = 'Manage';
        parent::init();
        $this->menu = array(
            'index' => array(
                'label' => Tk::g('Admin') ,
                'url' => array(
                    'index'
                )
            ) ,
        );
    }
    public function actionIndex() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $m = $this->modelName;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            $model->save();
        }
        $this->redirect(array(
            'view',
            'id' => $id
        ));
    }
}
