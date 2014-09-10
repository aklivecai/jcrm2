<?php
class StocksController extends Controller {
    public function init() {
        parent::init();
        $this->modelName = 'Stocks';
    }
    
    private function getPMovings($key, $product_id, $write = false) {
        $m = Movings::model();
        $m->initak($key);
        $_type = strtolower($m->getTypeName() . '-type');
        $cates = TakType::items($_type);
        if (Permission::iSWarehouses()) {
            $warehouse_id = Warehouse::getUserWare();
        } else {
            $warehouse_id = false;
        }
        $tags = ProductMoving::model()->getProductMovings($key, $product_id, $warehouse_id);
        $content = $this->renderPartial('pmovings', array(
            'typeid' => $key,
            'm' => $m,
            'tags' => $tags,
            'cates' => $cates,
            'model' => $this->_model,
        ) , true);
        if ($write) {
            echo $content;
            exit;
        }
        return $content;
    }
    
    public function actionViewProduct($id, $ajax = 0) {
        $model = Product::model()->findByPk($id);
        if ($model === null) $this->error();
        $this->_model = $model;
        $product_id = $model->primaryKey;
        $datas = array();
        $types = array(
            1 => 'purchase',
            2 => 'sell'
        );
        //ａｊａｘ获取内容直接退出
        if ($ajax > 0 && isset($types[$ajax])) {
            $this->getPMovings($ajax, $product_id, true);
        }
        foreach ($types as $key => $value) {
            $datas[$value] = $this->getPMovings($key, $product_id);
        }
        $this->render('viewproduct', array(
            'model' => $model,
            'datas' => $datas,
        ));
    }
    public $warehouse_id = false;
    public function actionIndex() {
        $m = 'Product';
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->warehouse_id = $model->warehouse_id;
        if (($this->warehouse_id <= 0 || $this->warehouse_id == '') && Permission::iSWarehouses()) {
            $this->warehouse_id = $model->warehouse_id = Warehouse::getUserWare();
        }
        $this->render($this->templates['index'], array(
            'model' => $model,
        ));
    }
    
    public function actionAdmin() {
        $m = 'Product';
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['admin'], array(
            'model' => $model,
        ));
    }
    
    public function actionToxls() {
        /**设置最大页码*/
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        Yii::app()->user->setState('pageSize', 100000);
        $m = 'Product';
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
            Tak::K($model->attributes, 'main1.log');
        }
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
        if ($tags->itemCount > 0) {
            $crite = $tags->getCriteria();
            $sql = $crite->condition;
            if ($sql) {
                $t = $crite->params;
                foreach ($t as $key => $value) {
                    $t[$key] = "'$value'";
                }
                $sql = strtr($sql, $t);
            }
            $totals = Product::getTotals($sql);
            $str = '总价格: :ptotal，总数量: :stotal';
            $headerText = strtr($str, array(
                ':stotal' => Tak::getNums($totals['stotal']) ,
                ':ptotal' => Tak::format_price($totals['ptotal']) ,
            ));
        }
        
        $xls = new Xls();
        $headers = array(
            'id' => array(
                'name' => '',
                'width' => 5
            ) ,
            'name' => array(
                'name' => $model->getAttributeLabel('name') ,
                'width' => 15
            ) ,
            'typeid' => array(
                'name' => $model->getAttributeLabel('typeid') ,
                'width' => 10
            ) ,
            'material' => array(
                'name' => $model->getAttributeLabel('material') ,
                'width' => 10
            ) ,
            'spec' => array(
                'name' => $model->getAttributeLabel('spec') ,
                'width' => 10
            ) ,
            'color' => array(
                'name' => $model->getAttributeLabel('color') ,
                'width' => 10
            ) ,
            'stock' => array(
                'name' => $model->getAttributeLabel('stocks') ,
                'width' => 12
            ) ,
            'price' => array(
                'name' => $model->getAttributeLabel('price') ,
                'width' => 12
            ) ,
            '小计' => array(
                'name' => '小计',
                'width' => 15
            ) ,
            '上个月结存' => array(
                'name' => '上个月结存',
                'width' => 15
            ) ,
            '本月进货' => array(
                'name' => '本月进货',
                'width' => 12
            ) ,
            '本月出货' => array(
                'name' => '本月出货存',
                'width' => 12
            ) ,
            '本月结存' => array(
                'name' => '本月结存',
                'width' => 12
            ) ,
        );
        foreach ($_temps as $key => $data) {
            $datas[] = array(
                'id' => ($key + 1) ,
                'name' => $data->name,
                'typeid' => Category::getProductName($data->typeid, " / ") ,
                'material' => $data->material,
                'spec' => $data->spec,
                'stock' => Tak::getNums($data->stock) ,
                'price' => Tak::getNums(($data->price)) ,
                '小计' => $data->total,
                '上个月结存' => $data->writeHistory(1, $this->warehouse_id) ,
                '本月进货' => $data->writeHistory(2, $this->warehouse_id) ,
                '本月出货' => $data->writeHistory(3, $this->warehouse_id) ,
                '本月结存' => $data->writeHistory(4, $this->warehouse_id) ,
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
            AdminLog::log(sprintf('结存明细导出<a href="%s" target="_blank">xls</a> , %s', $srdFile, $headerText));
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
