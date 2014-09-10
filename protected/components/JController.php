<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class JController extends CController {
    // public $layout = 'column2';
    public $menu = array();
    public $breadcrumbs = array();
    public $isAjax = false;
    
    protected $modelName = null;
    protected $_model = null;
    
    public $returnUrl = null;
    
    public function init() {
        parent::init();
        $this->isAjax = Yii::app()->request->isAjaxRequest;
        if ($this->isAjax) {
            $this->_setLayout('columnAjax');
            Yii::app()->clientScript->enableJavaScript = false;
        } else {
            $this->module->registerScripts();
            $this->layout = $this->module->layout;
            // Tak::KD($_GET);
            
        }
        
        $this->returnUrl = Yii::app()->request->getParam('returnUrl', null);
        if (!Tak::isGuest() && Tak::getFormid() != 1) {
            $this->redirect(Yii::app()->createUrl('site/index'));
        }
    }
    protected function _setLayout($layout = 'column2') {
        $this->layout = $layout;
    }
    public function afterRender($view, &$output) {
        if ($this->isAjax) {
            $this->_setLayout('columnAjax');
            Yii::app()->clientScript->reset();
        }
        parent::afterRender($view, $output);
    }
    
    public function filters() {
        return array(
            'accessControl - login',
            'ajaxOnly + search',
        );
    }
    public function allowedActions() {
        return 'error,login';
    }
    public function accessRules() {
        return array(
            array(
                'deny',
                'actions' => array() ,
                'users' => array(
                    '?'
                ) ,
            )
        );
    }
    
    public function loadModel($id, $recycle = false) {
        if ($this->_model === null || $recycle) {
            $m = $this->modelName;
            $m = $m::model();
            $this->_model = $m->findByPk($id);
            if ($this->_model === null) throw new CHttpException(404, '所请求的页面不存在。');
        }
        return $this->_model;
    }
    protected function performAjaxValidation($model) {
        
        $_tname = strtolower($this->modelName . '-form');
        if (isset($_POST['ajax']) && $_POST['ajax'] === $_tname) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionError() {
        $layout = 'column1';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) echo $error['message'];
            else $this->render('error', $error);
        }
    }
    
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id) ,
        ));
    }
    public function actionPreview($id) {
        $this->_setLayout('columnPreview');
        $this->render('review', array(
            'model' => $this->loadModel($id) ,
        ));
    }
    
    public function actionIndex() {
        $criteria = new CDbCriteria(array(
            'condition' => '1=1',
            'order' => 'modified_time DESC',
        ));
        $dataProvider = new CActiveDataProvider($this->modelName, array(
            'pagination' => array(
                'pageSize' => Yii::app()->params['defaultPageSize'],
            ) ,
            'criteria' => $criteria,
        ));
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    
    public function actionDelete($id) {
        $this->loadModel($id)->del();
        if (!isset($_GET['ajax'])) $this->redirect(isset($this->returnUrl) ? $this->returnUrl : array(
            'admin'
        ));
    }
    
    public function actionCreate() {
        $m = $this->modelName;
        $model = new $m;
        
        if (isset($_POST[$m])) {
            
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                if ($this->returnUrl) {
                    $this->redirect($this->returnUrl);
                } else {
                    if ($this->isAjax) {
                        if ($_POST['getItemid']) {
                            echo $model->primaryKey;
                            exit;
                        }
                    } else {
                        $this->redirect(array(
                            'view',
                            'id' => $model->primaryKey
                        ));
                    }
                }
            }
        } elseif (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $m = $this->modelName;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $this->redirect($this->returnUrl ? $this->returnUrl : array(
                    'view',
                    'id' => $model->primaryKey
                ));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    public function actionAdmin() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
    public function actionPrint($id) {
        $this->layout = 'colummPrint';
        $this->render('print', array(
            'model' => $this->loadModel($id) ,
        ));
    }
}

