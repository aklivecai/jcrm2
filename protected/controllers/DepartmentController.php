<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-07-01 11:04:46
 * @version $Id$
 */

class DepartmentController extends Controller {
    protected $tabs = null;
    public $modelName = 'Department';
    public $primaryName = 'itemid';
    public function init() {
        parent::init();
        if (!$this->isAjax) {
            $tabs = Department::getList();
            $_tabs = array();
            foreach ($tabs as $value) {
                $_tabs[$value['itemid']] = $value;
            }
            $this->tabs = $_tabs;
        }
    }
    
    public function loadModel($id) {
        if ($this->_model === null) {
            $id = $this->getSId($id);
            $m = $this->modelName;
            $m = $m::model();
            $this->_model = $m->findByPk($id);
            if ($this->_model === null) $this->error();
        }
        return $this->_model;
    }
    public function actionAdmin() {
        $len = count($this->tabs);
        if ($len == 0) {
            $this->redirect(array(
                'create'
            ));
        } else {
            $t = array_keys($this->tabs);
            $id = $t[0];
            $this->redirect(array(
                'view',
                'id' => $this->setSId($id) ,
            ));
        }
    }
    public function actoinCreate() {
        $m = $this->modelName;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $this->redirect(array(
                    'view',
                    'id' => $this->setSId($model->primaryKey) ,
                ));
            }
        }
        $this->render('create', array(
            'model' => $model
        ));
    }
    public function actionView($id) {
        $model = $this->loadModel($id);
        $workerData = null;
        $priceData = null;
        $strView = false;
        $m = 'DepartmentWorker';
        
        if (!$this->isAjax || ($this->isAjax && $_GET['ajax'] && $_GET['ajax'] == 'list-worker')) {
            $workerData = new $m('search');
            $arr = isset($_GET[$m]) ? $_GET[$m] : array();
            $arr['department_id'] = $model->itemid;
            $workerData->attributes = $arr;
            if ($this->isAjax) {
                $strView = '_list_worker';
            }
        }
        
        $m = 'DepartmentPrice';
        if (!$this->isAjax || ($this->isAjax && $_GET['ajax'] && $_GET['ajax'] == 'list-price')) {
            $priceData = new $m('search');
            $arr = isset($_GET[$m]) ? $_GET[$m] : array();
            $arr['department_id'] = $model->itemid;
            $priceData->attributes = $arr;
            if ($this->isAjax) {
                $strView = '_list_price';
            }
        }
        
        !$strView && $strView = $this->templates['view'];
        
        $this->render($strView, array(
            'model' => $model,
            'workerData' => $workerData,
            'priceData' => $priceData,
            'id' => $id,
        ));
    }
    
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        if (!$model->del()) {
            if ($this->isAjax) {
                exit;
            } else {
                $this->redirect(array(
                    'admin',
                ));
            }
        }
    }
    
    private $data = array(
        'worker' => 'DepartmentWorker',
        'price' => 'DepartmentPrice'
    );
    
    public function actionSaves($id, $action) {
        $model = $this->loadModel($id);
        $actions = array(
            'delworker',
            'delprice',
            'saveworker',
            'saveprice'
        );
        if (!in_array($action, $actions, 1)) {
            $this->error();
        }
        $itemid = isset($_GET['itemid']) ? $this->getSId($_GET['itemid']) : 0;
        $m = strpos($action, "worker") ? 'DepartmentWorker' : 'DepartmentPrice';
        $data = array();
        switch ($action) {
            case 'delworker':
            case 'delprice':
                $m::model()->deleteAllByAttributes(array(
                    'itemid' => $itemid,
                    'department_id' => $model->itemid,
                ));
            break;
            case 'saveworker':
            case 'saveprice':
                if ($itemid > 0) {
                    $_model = $m::model()->findByAttributes(array(
                        'itemid' => $itemid,
                        'department_id' => $model->itemid,
                    ));
                } else {
                    $_model = new DepartmentWorker('create');
                }
                $_model->attributes = $_POST['m'];
                if ($_model->validate() && $_model->save()) {
                    $_data = array(
                        'name' => $_model->name
                    );
                    if ($m == 'DepartmentPrice') {
                        $_data['price'] = $_model->price;
                    }
                    $data['data'] = $_data;
                } else {
                    $data['error'] = Tak::getMsgByErrors($_model->getErrors());
                }
            break;
            default:
            break;
        }
        if ($this->isAjax) {
            echo CJSON::encode($data);
            exit;
        } else {
            $this->redirect(array(
                'view',
                'id' => $id,
            ));
        }
    }
    public function actionCreateModel($id, $action) {
        $model = $this->loadModel($id);
        if (!isset($this->data[$action])) {
            $this->error();
        }
        $m = $this->data[$action];
        $_model = new $m('create');
        $_model->attributes = $_POST['m'];
        $_model->department_id = $model->itemid;
        if ($_model->validate() && $_model->save()) {
            $this->redirect(array(
                'view',
                'id' => $id,
            ));
        } else {
            $errors = $_model->getErrors();
            $url = isset($errors['name']) ? $this->createUrl('view', array(
                'id' => $id,
                $m . "[name]" => $_model->name
            )) : false;
            $this->render('/chip/error', array(
                'errors' => $errors,
                'url' => $url
            ));
        }
    }
    public function actionWindow($action, $index, $id = false) {
        if (!isset($this->data[$action])) {
            $this->error();
        }
        $len = count($this->tabs);
        if ($len == 0) {
            $script = '
                    if(confirm("当前没有车间信息，是否跳到添加车间页面？")){
                    if(window.opener == undefined) {
                        window.opener = window.dialogArguments;
                    }   
                    var s = window.opener.document.querySelector("a[href*=Department]");s.click();
                    };window.close();
                ';
            $this->_setLayout('columnWin');
            Tak::regScript('head', $script, CClientScript::POS_HEAD);
            $this->render('/chip/content', array());
            exit;
        }
        $this->_setLayout('columnWin');
        if (!$id) {
            $t = array_keys($this->tabs);
            $id = $this->setSId($t[0]);
        }
        $model = $this->loadModel($id);
        $m = $this->data[$action];
        
        $data = new $m('search');
        $arr = isset($_GET[$m]) ? $_GET[$m] : array();
        $arr['department_id'] = $model->itemid;
        $data->attributes = $arr;
        
        $this->render(sprintf("window", $action) , array(
            'model' => $model,
            'id' => $id,
            'data' => $data,
            'action' => $action,
            'index' => $index,
        ));
    }
    
    public function actionSelect($action, $q, $page_limit) {
        if (!isset($this->data[$action])) {
            $this->error();
        }
        if ($q == '*') {
            $q = "";
        }
        $pageSize = (int)$page_limit > 0 ? $page_limit : 10;
        $m = $this->data[$action];
        
        $model = new $m;
        $attributes = array(
            'itemid',
            'name'
        );
        $criteria = new CDbCriteria;
        if ($q) {
            $criteria->addSearchCondition('name', $q, true);
        }
        if ($action == 'price') {
            $attributes[] = 'price';
            if ($q) {
                $criteria->addSearchCondition('price', $q, true, 'OR');
            }
        }
        
        $data = array(
            'name' => $m,
            'data' => array(
                'attributes' => $attributes,
                'sort' => array(
                    'defaultOrder' => 'name DESC',
                ) ,
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $pageSize
                ) ,
            )
        );
        $dataProvider = new JSonActiveDataProvider($data['name'], $data['data']);
        $rs = $dataProvider->getArrayCountData();
        $str = '{"total":' . $rs['totalItemCount'] . ',"link_template":"temp.json?q={search-term}&page_limit={results-per-page}&page={page-number}"';
        if ($q) {
            $newMod = new $m('create');
            $newMod->attributes = array(
                'itemid' => '-1',
                'name' => $q,
            );
        } else {
            $newMod = false;
        }
        $json = $dataProvider->getJsonData(false, $newMod);
        
        $this->writeData($json);
    }
}
