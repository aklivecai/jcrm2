<?php
class Controller extends RController {
    public $layout = 'column2';
    
    public $menu = array();
    public $breadcrumbs = array();
    
    public $_model = null;
    
    protected $_modes = array();
    
    public $primaryName = 'itemid';
    public $modelName = '';
    
    public $isAjax = false;
    
    public $returnUrl = null;
    
    protected $dir = false;
    protected $templates = array(
        'create' => 'create',
        'update' => 'update',
        'admin' => 'admin',
        'view' => 'view',
        'index' => 'index',
        'preview' => '_view',
        'print' => 'print'
    );
    
    protected $_manifest = null;
    
    public function init() {
        parent::init();
        $this->isAjax = Yii::app()->request->isAjaxRequest;
        if ($this->isAjax) {
            $this->_setLayout('//layouts/columnAjax');
            Yii::app()->clientScript->enableJavaScript = false;
        } else {
            Yii::app()->clientScript->scriptMap = array(
                'jquery.js' => sprintf('%s_ak/js/jq.js', Yii::app()->params['staticUrl']) ,
                /*'jquery.min.js' => sprintf('%s_ak/js/jq.js', Yii::app()->params['staticUrl']) ,*/
            );
            /*
            // Yii::app()->bootstrap->register();          
            */
        }
        if ($this->dir) {
            $templates = $this->templates;
            foreach ($templates as $key => $value) {
                $templates[$key] = $this->dir . $value;
            }
            $this->templates = $templates;
        }
        if ($this->returnUrl == null) {
            $this->returnUrl = Yii::app()->request->getParam('returnUrl', null);
        }
    }
    public function _setLayout($layout = 'column2') {
        $this->layout = $layout;
    }
    public function setLayoutWin() {
        $this->_setLayout('//layouts/columnWindows');
    }
    /*解密ID*/
    public function getSId($id) {
        $result = Tak::getSId($id);
        if ($result == - 1) {
            $this->error('701', '参数不正确!');
        } elseif ($result == - 2) {
            $this->error('702', '数据超时!');
            exit;
        }
        return $result;
    }
    /*加密要传输的ID*/
    public function setSId($id) {
        $result = Tak::setSId($id);
        return $result;
    }
    //ajax信息返回值,1成功,0失败,info提示信息
    public function message($info, $status = true, $url = false) {
        if ($this->isAjax) {
            header('Content-Type: application/json');
            $tags = array(
                'status' => $status ? 1 : 0,
                'info' => $info,
            );
            if ($url) {
                $tags['url'] = $url;
            }
            echo CJSON::encode($tags);
            exit;
        } elseif (Tak::getQuery('dialog', false)) {
            $this->setLayoutWin();
            if (is_array($info)) {
                $info = Tak::getMsgByErrors($info);
            }
            $script = sprintf('closeWin({url:false,info:"%s"});', $info);
            $this->render('//chip/iframe', array(
                'script' => $script,
            ));
            exit;
        } else {
            if (is_array($info)) {
                $info = Tak::getMsgByErrors($info);
            }
            $this->errorE($info);
        }
    }
    /**
     * [loadModel description]
     * @param  [type]  $id     [description]
     * @param  boolean $m      [模块]
     * @param  boolean $isload [是否保存加载]
     * @return [type]          [返回查找的信息]
     */
    public function loadModels($id, $m = false, $isload = false) {
        if (!$m) {
            $m = $this->modelName;
        }
        if ($isload || !isset($this->_modes[$m])) {
            $id = $this->getSId($id);
            $model = $m::model();
            $model = $model->setGetCU()->findByPk($id);
            if ($model === null) {
                return null;
            } else {
                $model->setGetCU();
            }
            $this->_modes[$m] = $model;
        }
        return $this->_modes[$m];
    }
    public function afterRender($view, &$output) {
        if ($this->isAjax) {
            Yii::app()->clientScript->reset();
        }
        parent::afterRender($view, $output);
    }
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'updateOwn + update', // Apply this filter only for the update action.
            'deleteOwn + delete', // Apply this filter only for the update action.
            'rights',
        );
    }
    
    public function allowedActions() {
        return 'views,error';
    }
    
    public function filterUpdateOwn($filterChain) {
        $id = $this->getSId($_GET['id']);
        $obj = $this->loadModel($id);
        
        if (Yii::app()->user->checkAccess('UpdateOwn', array(
            'userid' => $obj->primaryKey
        ))) $filterChain->removeAt(1);
        $filterChain->run();
    }
    
    public function filterDeleteOwn($filterChain) {
        // $params=array('item'=>$model); // set params array for Rights' BizRule
        $id = $this->getSId($_GET['id']);
        $obj = $this->loadModel($id);
        if (Yii::app()->user->checkAccess('DeleteOwn', array(
            'manageid' => $obj->primaryKey
        ))) $filterChain->removeAt(1);
        $filterChain->run();
    }
    /**
     * [loadModel description]
     * @param  [type]  $id
     * @param  boolean $recycle 状态
     * @param  boolean $notcu 是否限制为当前用户
     * @return [type] $model
     */
    public function loadModel($id, $recycle = false, $notcu = false) {
        if ($this->_model === null) {
            $id = $this->getSId($id);
            $m = $this->modelName;
            $m = $m::model();
            if ($recycle) {
                $m->setRecycle();
            }
            if ($notcu) {
                $m->setGetCU();
            }
            $m = $m->findByPk($id);
            if ($m === null) {
                $this->error();
            }
            if ($notcu) {
                $m->setGetCU();
            }
            $this->_model = $m;
        }
        return $this->_model;
    }
    
    protected function performAjaxValidation($model) {
        $_tname = strtolower($this->modelName . '-form');
        if (isset($_POST['ajax']) && ($_POST['ajax'] === $_tname || $_POST['ajax'] == 'mod-form')) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) echo $error['message'];
            else $this->render('error', $error);
        }
    }
    
    public function actionView($id) {
        $this->render($this->templates['view'], array(
            'model' => $this->loadModel($id) ,
            'id' => $id,
        ));
    }
    
    public function actionViews($id) {
        $this->render('views', array(
            'model' => $this->loadModel($id) ,
            'id' => $id,
        ));
    }
    public function actionPreview($id, $uuid = false, $status = false, $not = true) {
        if (!$this->isAjax) {
            $this->_setLayout('//layouts/columnPreview');
        }
        if ($uuid && Tak::getEid($uuid) != $id) {
            // $not = false;
            // $status = false;
            
            
        }
        $this->render($this->templates['preview'], array(
            'model' => $this->loadModel($id, $status, $not) ,
        ));
    }
    
    public function actionIndex() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['index'], array(
            'model' => $model,
        ));
    }
    
    public function actionDelete($id) {
        $this->loadModel($id)->del();
        if (!isset($_GET['ajax'])) $this->redirect(isset($this->returnUrl) ? $this->returnUrl : array(
            'admin'
        ));
    }
    
    protected function beforeCreate($model) {
    }
    public function actionCreate() {
        $m = $this->modelName;
        $model = new $m('create');
        if (isset($_POST[$m])) {
            $this->performAjaxValidation($model);
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $this->beforeCreate($model);
                if ($this->returnUrl) {
                    $this->redirect($this->returnUrl);
                } else {
                    if ($this->isAjax) {
                        if (isset($_POST['getItemid'])) {
                            echo $model->primaryKey;
                            exit;
                        }
                    } else {
                        $this->redirect(array(
                            'view',
                            'id' => $this->setSId($model->primaryKey) ,
                        ));
                    }
                }
            }
        } elseif (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['create'], array(
            'model' => $model,
        ));
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->scenario = 'update';
        $m = $this->modelName;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $this->redirect($this->returnUrl ? $this->returnUrl : array(
                    'view',
                    'id' => $this->setSId($model->primaryKey) ,
                ));
            }
        }
        $this->render($this->templates['update'], array(
            'model' => $model,
            'id' => $id,
        ));
    }
    // 回收站
    public function actionRecycle() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->sName.= Tk::g('Recycle');
        $model->setRecycle();
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['admin'], array(
            'model' => $model,
        ));
    }
    // 还原
    public function actionRestore($id) {
        $model = $this->loadModel($id, true);
        $model->setRestore();
        $this->redirect(array(
            'recycle'
        ));
    }
    // 彻底删除
    public function actionDel($id) {
        $this->loadModel($id, 1)->delete();
        if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            'recycle'
        ));
    }
    
    public function actionAdmin() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['admin'], array(
            'model' => $model,
        ));
    }
    
    public function actionPrint($id) {
        $this->layout = '//layouts/colummPrint';
        $this->render($this->templates['print'], array(
            'model' => $this->loadModel($id) ,
        ));
    }
    
    public function actionSelectById($id = false, $_modelName = false) {
        if (!is_numeric($id)) {
            $message = Tk::g('Illegal operation');
            $this->error(403, $message);
            exit;
        }
        // 通过方法传递,对应的模块名字
        if (!$_modelName) {
            $m = $_modelName;
        } else {
            $m = $this->modelName;
        }
        $msg = $this->loadModel($id);
        $data = $msg->attributes;
        $data['itemid'] = $msg->primaryKey;
        $data['title'] = $msg->{$msg->linkName};
        if ($msg != null) {
            $str = json_encode($data);
            $this->writeData('{data:[' . $str . ']}');
        }
    }
    
    public function actionSelect($id = 0, $page_limit = 10, $q = '*', $not = false) {
        (int)$id > 0 && $this->actionSelectById($id);
        $pageSize = (int)$page_limit > 0 ? $page_limit : 10;
        $q = Yii::app()->request->getQuery('q', false);
        $data = $this->getSelectOption($q, $not);
        $data['data']['pagination']['pageSize'] = $pageSize;
        $dataProvider = new JSonActiveDataProvider($data['name'], $data['data']);
        $rs = $dataProvider->getArrayCountData();
        $str = '{"total":' . $rs['totalItemCount'] . ',"link_template":"movies.json?q={search-term}&page_limit={results-per-page}&page={page-number}"';
        // $this->render('/site/ie6',array(
        //  'model'=>$model,
        // ));exit;
        
        $this->writeData($dataProvider->getJsonData());
    }
    
    public function actionGetTop($id, $top = 5, $view = 'view') {
        
        $top = (int)$top > 0 ? (int)$top : 10;
        $msg = $this->loadModel($id);
        $tags = $msg->getNP(false, $top);
        
        $this->_setLayout('//layouts/columnAjax');
        Yii::app()->clientScript->enableJavaScript = false;
        
        $this->render('/chip/list-top', array(
            'model' => $msg,
            'tags' => $tags,
            'view' => $view
        ));
    }
    
    protected function getSelectOption($q, $not = false) {
        $m = $this->modelName;
        $model = new $m;
        
        $key = $model->primaryKey();
        $linkName = $model->linkName;
        
        $attributes = array(
            $key,
            $model->linkName
        );
        $result = array(
            'name' => $m,
            'data' => array(
                'attributes' => $attributes,
                'attributeAliases' => array(
                    $key => 'itemid',
                    $linkName => 'title'
                ) ,
                'sort' => array(
                    'defaultOrder' => 'add_time DESC,' . $linkName . ' ASC',
                ) ,
            )
        );
        $criteria = new CDbCriteria;
        if ($q) {
            $criteria->addSearchCondition('user_name', $q, true);
        }
        if ($not) {
            $_not = explode(',', $not);
            if (is_array($_not) && count($_not) > 0) {
                $criteria->addNotInCondition($model->primaryKey() , $_not);
            }
        }
        $result['data']['criteria'] = $criteria;
        return $result;
    }
    
    public function errorE($msg = '非法操作') {
        $this->error(202, $msg);
    }
    public function error($code = 404, $msg = '所请求的页面不存在。') {
        throw new CHttpException($code, $msg);
    }
    
    public function writeData($data) {
        header('Content-Type: application/json');
        $callback = $_GET['callback'];
        $str = $callback . '(' . $data . ');';
        echo ($str);
        exit;
    }
    
    public function dow($file) {
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $file . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    }
}

