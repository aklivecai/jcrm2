<?php
class CategoryController extends Controller {
    protected $m = null;
    protected $cateUrl = false;
    public $defaultAction = 'admin';
    public function init() {
        $this->m = $this->getType();
        $this->modelName = 'Category';
        $this->primaryName = 'catid';
        $this->cateUrl = $this->getLink();
        parent::init();
    }
    
    public function filters() {
        // $arr = parent::filters();
        $arr = array();
        $arr[] = 'selectOwn + admin';
        $arr[] = 'rights';
        return $arr;
    }
    public function filterselectOwn($filterChain) {
        // $params=array('item'=>$model); // set params array for Rights' BizRule
        $params = array(
            'm' => Tak::getQuery('m') ,
            'action' => Tak::getQuery('action') ,
        );
        if (Tak::checkAccess('ProductCateSelect', $params)) {
            $filterChain->removeAt(1);
        }
        $filterChain->run();
    }
    public function loadModel($id, $isload = false) {
        if ($isload || $this->_model === null) {
            $m = $this->modelName;
            
            $m = $m::model();
            $m = $m->findByAttributes(array(
                'catid' => $id,
                'module' => strtolower($this->m)
            ));
            if ($m == null) {
                $this->error();
            }
            $this->_model = $m;
        }
        return $this->_model;
    }
    
    public function allowedActions() {
        $result = array(
            parent::allowedActions() ,
            'select'
        );
        return implode(',', $result);
    }
    protected function getLink($action = 'Admin') {
        return $this->createUrl($action, array(
            'm' => $this->m
        ));
    }
    protected function getType() {
        $m = Tak::getQuery('m', false);
        if (!$m || !Category::getModel($m)) {
            $this->error();
        }
        return ucwords($m);
    }
    
    public function actionAdmin($action = false, $id = false) {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes();
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $model->setModel($this->m);
        if ($action == 'select') {
            $this->setLayoutWin();
            $view = '_show';
        } else {
            $view = $this->templates['admin'];
        }
        $this->render($view, array(
            'model' => $model,
            'id' => $id,
            'action' => $action,
        ));
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->scenario = 'update';
        $m = $this->modelName;
        if (isset($_POST[$m])) {
            if ($model->parentid != $_POST[$m]['parentid']) {
                $model->setChangePid($model->parentid);
            }
            $model->attributes = $_POST[$m];
            $model->setModel($this->m);
            if ($model->save()) {
                $this->redirect($this->getLink() . '&id=' . $model->getItemid());
            }
        }
        $this->render($this->templates['update'], array(
            'model' => $model,
        ));
    }
    
    public function actionCreate($action = false) {
        $m = $this->modelName;
        $model = new $m('create');
        if (isset($_POST[$m])) {
            $this->performAjaxValidation($model);
            $model->attributes = $_POST[$m];
            $model->setModel($this->m);
            if ($model->save()) {
                if ($this->returnUrl) {
                    $this->redirect($this->returnUrl);
                } elseif ($action == 'select') {
                    $turl = $_SERVER['HTTP_REFERER'];
                    if (strpos($turl, 'product')) {
                        $turl.=sprintf("?Product[typeid]=%s",$model->catid);
                        $this->redirect($turl);
                    }                    
                    exit;
                    $this->setLayoutWin();
                    $this->render('create', array(
                        'model' => $model,
                    ));
                    exit;
                } else {
                    if ($this->isAjax) {
                        if (isset($_POST['getItemid'])) {
                            echo $model->primaryKey;
                            exit;
                        }
                    } else {
                        $this->redirect($this->getLink() . '&id=' . $model->getItemid());
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
    
    public function actionList($m, $id = false) {
        $this->render('list', array(
            'data' => $data,
        ));
    }
}
