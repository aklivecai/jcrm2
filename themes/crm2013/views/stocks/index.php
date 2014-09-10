<?php
/* @var $this StocksController */
/* @var $model Stocks */

$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'index'
    ) ,
);
$items = Tak::getListMenu();
Tak::regScript('tak', 'isprintf = true;istoxls = true;', CClientScript::POS_END);
?>
<div class="row-fluid">
    <div class="span12">
    <div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g(array(
    $this->modelName
)) ?></h1>
    </div>  

<div class="block-fluid clearfix">
<?php $this->renderPartial('/product/_search', array(
    'model' => $model,
    'warehouse' => true
)); ?>

<?php
$options = Tak::gredViewOptions(false);
$tags = $model->search();
$str = '';
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
    $str.= '<div class="clearfix"><i class="icon-tasks"></i>总价格: <span class="badge badge-important">:ptotal</span>   <i class="icon-tasks"></i>总数量: <span class="badge badge-warning">:stotal</span>     </div><div class="dr"><span></span></div>';
    $str = strtr($str, array(
        ':stotal' => Tak::getNums($totals['stotal']),
        ':ptotal' => Tak::format_price($totals['ptotal']) ,
    ));
    $options['template'] = $options['template'] . $str;
}
$columns = array(
    Tak::getAdminPageCol(array(
        'template' => '{preview}',
        'buttons' => array(
            'preview' => array(
                'label' => '',
                'url' => 'Yii::app()->controller->createUrl("viewProduct", array("id"=>$data->primaryKey))',
                'options' => array(
                    'title' => '浏览',
                    'class' => 'icon-eye-open'
                ) ,
            )
        )
    )) ,
    'name' => 'name',
    array(
        'name' => 'typeid',
        'value' => 'Category::getProductName($data->typeid," / ")',
    ) ,
    array(
        'name' => 'material',
        'value' => '$data->material',
    ) ,
    array(
        'name' => 'spec',
        'value' => '$data->spec',
    ) ,
    array(
        'name' => 'color',
        'value' => '$data->color',
    ) ,
    'note',
    
    'warehouse' => array(
        'name' => 'warehouse_id',
        'value' => 'Warehouse::deisplayName($_GET[Product][warehouse_id])'
    ) ,
    array(
        'name' => '库存',
        'type' => 'raw',
        'value' => 'Tak::tagNum($data->stock)',
    ) ,
    'unit',
    array(
        'name' => 'price',
        'value' => 'Tak::format_price($data->price)',
    ) ,
    array(
        'name' => '小计',
        'value' => 'Tak::format_price($data->total)',
    ) ,
    array(
        'name' => '上个月结存',
        'value' => '$data->writeHistory(1,Yii::app()->getController()->warehouse_id)',
    ) ,
    array(
        'name' => '本月进货',
        'value' => '$data->writeHistory(2,Yii::app()->getController()->warehouse_id)',
    ) ,
    array(
        'name' => '本月出货',
        'value' => '$data->writeHistory(3,Yii::app()->getController()->warehouse_id)',
    ) ,
    array(
        'name' => '本月结存',
        'value' => '$data->writeHistory(4,$_GET["warehouse_id"])',
    ) ,
);
if (!isset($_GET['Product']['warehouse_id'])||$_GET['Product']['warehouse_id']<0) {
    unset($columns['warehouse']);
}

$options['columns'] = $columns;
$options['dataProvider'] = $tags;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
        </div>
    </div>
</div>