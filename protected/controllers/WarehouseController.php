<?php
class WarehouseController extends Controller {
    public $defaultAction = 'admin';
    public $modelName = 'Warehouse';
    public $warehouseus = null;
    
    public $returnUrl = array(
        "admin"
    );
    public function init() {
        parent::init();
    }
    public function loadModel($id, $isload = false) {
        if ($isload || $this->_model === null) {
            $m = $this->modelName;
            $m = $m::model();
            $m = $m->findByAttributes(array(
                'itemid' => $id
            ));
            if ($m === null) {
                $this->error();
            }
            $this->_model = $m;
        }
        return $this->_model;
    }
    
    private function getUs() {
        if ($this->warehouseus == null) {
            $this->warehouseus = Permission::getUWarehouses();
        }
        return $this->warehouseus;
    }
    private function getUser($arr) {
        !is_array($arr) && $arr = array_filter(explode(',', $arr));
        $data = array();
        $warehouseus = $this->getUs();
        $result = '';
        foreach ($arr as $value) {
            if (isset($warehouseus[$value])) {
                $data[] = $value;
            }
        }
        $name = implode(',', $data);
        if ($name) {
            $result = sprintf('%s,', $name);
        }
        return $result;
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
    
    public function actionCreate() {
        $m = $this->modelName;
        $warehouseus = $this->getUs();
        if (isset($_POST[$m]) && is_array($_POST[$m]['user_name'])) {
            $names = $this->getUser($_POST[$m]['user_name']);
            if ($names != '') {
                $_POST[$m]['user_name'] = $names;
            }
        }
        parent::actionCreate();
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->scenario = 'update';
        $m = $this->modelName;
        $warehouseus = $this->getUs();
        if (isset($_POST[$m])) {
            $names = $this->getUser($_POST[$m]['user_name']);
            $_POST[$m]['user_name'] = $names;
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $this->redirect($this->returnUrl ? $this->returnUrl : array(
                    'view',
                    'id' => $this->setSId($model->primaryKey) ,
                ));
            }
        }
        if ($model->user_name != '') {
            $model->user_name = explode(',', $model->user_name);
        }
        $this->render($this->templates['update'], array(
            'model' => $model,
            'id' => $id,
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
        exit;
        if ($this->isAjax) {
            exit;
        }
        $this->redirect(array(
            "admin"
        ));
    }
    
    public function actionListorder($id, $action) {
        $model = $this->loadModel($id);
        $m = null;
        if ($action == 'up') {
            $m = $model->getNext(true);
        } elseif ($action == 'dw') {
            $m = $model->getPrevious(true);
        }
        // Tak::KD($m,1);
        if ($m) {
            $m = $this->loadModel($m, true);
            $o = $m->listorder;
            $o1 = $model->listorder;
            if ($o1 == $o) {
                $o = $action == 'up' ? ($o + 1) : ($o - 1);
            }
            if ($o < 0) {
                $o = 0;
            }
            $m->listorder = $model->listorder;
            $m->save();
            $model->listorder = $o;
            $model->save();
        }
        $this->redirect(array(
            "admin",
            'id' => $id
        ));
    }
}
