<?php
class MovingsController extends Controller {
    public $type = 1;
    public $modelName = 'Movings';
    protected $typename = null;
    protected $cates = null;
    
    protected $dir = '//movings/';
    
    public function init() {
        parent::init();
        $this->typename = $typename = Tak::getMovingsType($this->type);
        $_type = $typename . '-type';
        $_type = strtolower($_type);
        $this->cates = TakType::items($_type);
    }
    public function loadModel($id = false, $recycle = false) {
        $id = $this->getSId($id);
        if ($this->_model === null) {
            if ($id) {
                $m = $this->modelName;
                $model = $m::model();
                if ($recycle) {
                    $model->setRecycle();
                }
                $this->_model = $model->findByPk($id);
            }
            if ($this->_model === null) {
                $this->error();
            } else {
                $this->_model->initak($this->type);
            }
        }
        return $this->_model;
    }
    
    protected function afterAction($action) {
        if ($action->id == 'update') {
        }
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
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
        $this->render('/movings/_info', array(
            'model' => $model,
            'id' => $id,
        ));
    }
    
    public function actionCreate() {
        $m = $this->modelName;
        $model = new $m('create');
        $model->initak($this->type);
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            $script = false;
            if ($model->validate() && $model->checkProducts(isset($_POST['Product']) ? $_POST['Product'] : false)) {
                if (Permission::iSWarehouses()) {
                    $warehouse_ids = Warehouse::getUserWare();
                    if (!is_array($warehouse_ids) || !isset($warehouse_ids[$model->warehouse_id])) {
                        $this->error();
                    }
                }
                if ($model->validate() && $model->save()) {
                    $script = sprintf('parent.window.location.href="%s";', $this->createUrl('view', array(
                        'id' => $model->primaryKey
                    )));
                } else {
                    Tak::KD($model->getErrors());
                }
            } else {
                Tak::KD($model->getErrors());
            }
            $this->setLayoutWin();
            $this->render('/chip/iframe', array(
                'model' => $model,
                'script' => $script,
            ));
            exit;
        } elseif (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['create'], array(
            'model' => $model,
        ));
    }
    
    public function actionView($id, $affirm = false) {
        $model = $this->loadModel($id);
        $this->render($this->templates['view'], array(
            'model' => $model,
            'affirm' => $affirm,
            'id' => $id,
        ));
    }
    /**
     * 是否有权限修改，已经确认入库的产品
     * @return [type] [description]
     */
    protected function checkAccess() {
        return Tak::checkSuperuser() || Tak::checkAccess('Up.movings');
    }
    
    public function actionUpproduct($id) {
        $model = $this->loadModel($id);
        if ($model->isAffirm() && !$this->checkAccess()) {
            $this->error(202, '沒有权限操作!');
        }
        // $model->warehouse_id = $this->setSId($model->warehouse_id);
        $this->render('/movings/upproduct', array(
            'model' => $model,
            'id' => Tak::setSId($id) ,
        ));
    }
    
    public function actionDelProdcut($id, $itemid) {
        $model = $this->loadModel($id);
        if ($model->isAffirm() && !$this->checkAccess()) {
            $this->error(202, '沒有权限操作!');
        }
        $model->delProduct($this->getSId($itemid));
    }
    /**
     * 保存出入库产品
     * @param  int $id 出入库编号
     * @return [type]     新增产品返回id,修改产品数量信息返回空,错误信息返回josn错误对象数组
     */
    public function actionSaveProdcut($id) {
        $model = $this->loadModel($id);
        if ($model->isAffirm() && !$this->checkAccess()) {
            $this->error(202, '沒有权限操作!');
        }
        $m = 'm';
        $result = '';
        if (isset($_POST[$m])) {
            $_model = new ProductMoving();
            $_model->type = $model->type;
            $_model->attributes = $_POST[$m];
            $_model->product_id = $this->getSId($_model->product_id);
            $_model->movings_id = 888;
            $_model->warehouse_id = 888;
            // Tak::KD($_model->attributes,1);
            if ($_model->validate()) {
                $result = $model->saveProduct($_model->attributes);
            } else {
                $result = $_model->getErrors();
            }
        }
        if (is_array($result)) {
            header('Content-Type: application/json');
            json_encode($result);
        } else {
            echo $result;
        }
        exit;
    }
    
    public function actionAdmin() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->initak($this->type);
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        if (($model->warehouse_id <= 0 || $model->warehouse_id == '') && Permission::iSWarehouses()) {
            $model->warehouse_id = Warehouse::getUserWare();
        }
        $this->render($this->templates['admin'], array(
            'model' => $model,
        ));
    }
    
    public function actionAffirm($id) {
        $this->loadModel($id)->affirm();
        if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            'view',
            'id' => $id
        ));
    }
    
    public function writeProduct($data) {
        $htmls = array(
            '<ol class="mov-products">'
        );
        foreach ($data as $key => $value) {
            $numbers = Tak::getNums($value['numbers']);
            if ($value['unit']) {
                $numbers.= sprintf('%s', $value['unit']);
            }
            // $numbers =Tak::tagNum($numbers,'label-info');
            $htmls[] = sprintf("<li>%s - %s</li>", $value['name'], $numbers);
        }
        $htmls[] = '</ol>';
        $html = implode("", $htmls);
        if (count($data) > 3) {
            $html = sprintf('<div class="mov-product">%s<a class="not-printf more-product">&gt;&gt;更多</a><a class="not-printf more-product-hide">&gt;&gt;收起</a></div>', $html);
        }
        return $html;
    }
    public function actionPrint($id) {
        $this->layout = '//layouts/colummPrint';
        $str = $this->templates['print'];
        $fid = Tak::getFormid();
        if ($fid == 5139) {
            $dir = $fid;
            $str = sprintf("/print/%s/movings/print", $dir);
        }
        $this->render($str, array(
            'fid' => $fid,
            'model' => $this->loadModel($id) ,
        ));
    }
    
    public $warehouse_id = false;
    
    public function actionToxls() {
        /**设置最大页码*/
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        Yii::app()->user->setState('pageSize', 100000);
        
        $m = $this->modelName;
        $model = new $m('search');
        $model->initak($this->type);
        $model->unsetAttributes(); // clear any default values
        
        $this->warehouse_id = $model->warehouse_id;
        if (($this->warehouse_id <= 0 || $this->warehouse_id == '') && Permission::iSWarehouses()) {
            $this->warehouse_id = $model->warehouse_id = Warehouse::getUserWare();
        }
        
        $datas = array();
        $tags = $model->search();
        $_temps = $tags->getData();
        /**
         * 还原原来页码打小
         */
        Yii::app()->user->setState('pageSize', $pageSize);
        $headerText = '';
        
        $xls = new Xls();
        $headers = array(
            'id' => array(
                'name' => '',
                'width' => 5
            ) ,
            '产品' => array(
                'name' => '产品',
                'width' => 25
            ) ,
            '金额' => array(
                'name' => '金额',
                'width' => 15
            ) ,
            'warehouse_id' => array(
                'name' => $model->getAttributeLabel('warehouse_id') ,
                'width' => 10
            ) ,
            'enterprise' => array(
                'name' => $model->getAttributeLabel('enterprise') ,
                'width' => 10
            ) ,
            'numbers' => array(
                'name' => $model->getAttributeLabel('numbers') ,
                'width' => 10
            ) ,
            'us_launch' => array(
                'name' => $model->getAttributeLabel('us_launch') ,
                'width' => 12
            ) ,
            'time' => array(
                'name' => $model->getAttributeLabel('time') ,
                'width' => 12
            ) ,
            'note' => array(
                'name' => $model->getAttributeLabel('note') ,
                'width' => 15
            ) ,
        );
        foreach ($_temps as $key => $data) {
            $strProduct = array();
            $_products = $data->getProducts();
            foreach ($_products as $key => $value) {
                $numbers = Tak::getNums($value['numbers']);
                if ($value['unit']) {
                    $numbers.= sprintf('%s', $value['unit']);
                }
                $strProduct[] = sprintf("%s - %s", $value['name'], $numbers);
            }
            $datas[] = array(
                'id' => ($key + 1) ,
                '产品' => implode("\n", $strProduct) ,
                '金额' => Tak::format_price($data->getTotal()) ,
                'warehouse_id' => Warehouse::deisplayName($data->warehouse_id) ,
                'enterprise' => $data->enterprise,
                'numbers' => $data->numbers,
                'us_launch' => $data->us_launch,
                'time' => Tak::timetodate($data->time) ,
                'note' => $data->note,
            );
        }
        // Tak::KD($datas);
        $file = $xls->toXLs(array(
            'headers' => $headers,
            'datas' => $datas,
            'headerText' => $headerText,
        ));
        if ($file) {
            // 记录导出的文件,相对路径
            $srdFile = Tak::srcUrl($file);
            AdminLog::log(sprintf('%s导出<a href="%s" target="_blank">xls</a> , %s', Tk::g($model->sName) , $srdFile, $headerText));
            $this->dow($file);
        }
        ob_end_flush();
        exit;
    }
    public function dow($file) {
        $fname = pathinfo($file);
        $fname = $fname['basename'];
        Tak::down_file($file, $fname);
        exit;
    }
}

