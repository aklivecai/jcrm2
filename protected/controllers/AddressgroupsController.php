<?php
class AddressGroupsController extends Controller {
    public $defaultAction = 'admin';
    public $modelName = 'AddressGroups';
    public $primaryName = 'address_groups_id';
    public $returnUrl = array(
        "admin"
    );    
    public function init() {
        parent::init();
    }
    public function actionAdmin($id = false) {
        $m = $this->modelName;
        $model = new $m;
        $data = $m::getDataProvider();
        $this->render('admin', array(
            'data' => $data,
            'id' => $id,
            'model' => $model,
        ));
    }
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        $result = $model->del();
        if ($result) {
            // Tak::KD($model->getError('name'));
            echo $result;
        } else {

        }
        if ($this->isAjax) {
            exit;
        }
        $this->redirect(array(
            "admin"
        ));
    }       
}
