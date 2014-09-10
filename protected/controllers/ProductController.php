<?php
class ProductController extends Controller {
    public function init() {
        parent::init();
        $this->modelName = 'Product';
    }
    // 彻底删除
    public function actionDel($id) {
        $this->loadModel($id, 1)->delete();
        if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            'Recycle'
        ));
    }
    
    public function actionToJson($id) {
        $model = $this->loadModel($id);
        $data = array();
        $data[] = $model->attributes;
        $script = ';closeWin(' . CJSON::encode($data) . ');';
        $this->setLayoutWin();
        $this->render('/chip/iframe', array(
            'script' => $script,
        ));
    }
    protected function beforeCreate($model) {
        if (Tak::getQuery('action') == 'window') {
            $this->redirect(array(
                'tojson',
                'id' => $this->setSId($model->primaryKey) ,
            ));
        }
    }
    public function actionCreate($action = false) {
        if ($action == 'window') {
            $this->setLayoutWin();
            if (isset($_POST[$m])) {
                $this->returnUrl = $this->createUrl('toJson');
            }
        }
        parent::actionCreate();
    }
    
    public function actionSelect() {
        $pageSize = Yii::app()->request->getQuery('page_limit', 10);
        $page = Yii::app()->request->getQuery('page', 1);
        $q = Yii::app()->request->getQuery('q', '*');
        
        $itemid = Yii::app()->request->getQuery('itemid', '0');
        
        $notitemid = Yii::app()->request->getQuery('not', false);
        if (!is_numeric($itemid)) {
            $itemid = '0';
        }
        $criteria = new CDbCriteria;
        if ($q != '*') {
            $criteria->addSearchCondition('name', $q);
        }
        if ($itemid != '0') {
            $criteria->addCondition('itemid=:itemid');
            $criteria->params[':itemid'] = $itemid;
        }
        if ($notitemid) {
            $notitemid = explode(",", $notitemid);
            $notitemid = array_filter($notitemid);
            $criteria->addNotInCondition("itemid", $notitemid);
        }
        $dataProvider = new JSonActiveDataProvider($this->modelName, array(
            'attributes' => array(
                'itemid',
                'name',
                'material',
                'spec',
                'color',
                'price'
            ) ,
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
            ) ,
            'pagination' => array(
                'pageSize' => $pageSize
            ) ,
        ));
        $rs = $dataProvider->getArrayCountData();
        $this->writeData($dataProvider->getJsonData());
    }
    /**
     * 选择产品,
     * @param  int $type 类型，1.入库，２.出库
     * @return [type]       [description]
     */
    public function actionWindow($type = false) {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes();
        if ($type) {
            $type = $this->getSId($type);
            $type != 1 && $type = 2;
        }
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        if (!$this->isAjax) {
            $this->setLayoutWin();
        }
        $this->render('window', array(
            'model' => $model,
            'typeid' => $type,
        ));
    }
}
