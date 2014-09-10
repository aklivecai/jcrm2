<?php
class ImportController extends Controller {
    public $types = array(
        'product' => 'JImportProduct',
        'addressBook' => 'JImportAddressbook',
        'clientele' => 'JImportClientele',
        'order' => 'JImportOrder',
    );
    public $type = null;
    public function init() {
        parent::init();
    }
    public function allowedActions() {
        return 'message,upload,import';
    }
    private function getType($action) {
        return isset($this->types[$action]) ? $this->types[$action] : null;
    }
    public function loadModel($action) {
        $m = $this->getType($action);
        if ($m === null) {
            $this->error();
        }
        $model = new $m();
        return $model;
    }
    public function getTemplate($action) {
        $url = Yii::app()->createUrl('upload/importModel/');
        switch ($action) {
            case 'product':
                $url.= '/商品资料导入模板.xls';
            break;
            case 'addressBook':
                $url.= '/通讯录导入模板.xls';
            break;
            case 'clientele':
                $url.= '/客户资料导入模板.xls';
            break;
            case 'order':
                $url.= '/Order.xls';
            default:
            break;
        }
        return $url;
    }
    private function action($action) {
        $model = $this->loadModel($action);
        $url = $this->getTemplate($action);
        $this->render('index', array(
            'model' => $model,
            'action' => $action,
            'url' => $url,
        ));
    }
    public function actionClears($action) {
        $model = $this->loadModel($action);
        Tak::deleteUCache($action, $tags);
        $this->redirect($action);
    }
    public function actionProduct() {
        $action = 'product';
        $this->action($action);
    }
    public function actionOrder() {
        $action = 'order';
        $this->action($action);
    }
    public function actionClientele() {
        $action = 'clientele';
        $this->action($action);
    }
    public function actionAddressBook() {
        $action = 'addressBook';
        $this->action($action);
    }
    public function actionImport($action) {
        $model = $this->loadModel($action);
        if (isset($_POST[$model->model])) {
            // Tak::KD(count($_POST[$model->model]));
            $result = $model->load($_POST[$model->model]);
            if ($result) {
                $model->import();
            }
            $this->_setLayout('//layouts/columnWin');
            $this->render('submit', array(
                'model' => $model,
                'action' => $actoin,
            ));
        }
    }
    public function actionMessage($action) {
        $model = $this->loadModel($action);
        $message = Tak::getFlash();
        if ($message == '') {
            $this->redirect(array(
                $action
            ));
        }
        $this->render('message', array(
            'model' => $model,
            'message' => $message,
        ));
    }
    public function actionUpload($action) {
        $model = $this->loadModel($action);
        $model->file = CUploadedFile::getInstanceByName('file');
        $data = false;
        $errors = false;
        if ($model->validate() && $model->save()) {
            //读取xls数据
            $data = $model->getTags();
            $tags = array(
                'time' => Tak::now() ,
                'data' => $data,
            );
            //保存到缓存中
            Tak::setUCache($action, $tags);
        } else {
            $error = $model->getErrors();
            foreach ($error as $key => $value) {
                foreach ($value as $k => $val) {
                    $errors[] = $val;
                }
            }
        }
        $this->setLayoutWin();
        $this->render('form', array(
            'model' => $model,
            'data' => $data,
            'errors' => $errors,
            'action' => $action,
        ));
    }
}
