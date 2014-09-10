<?php
$this->pageTitle = '库存';
$m = 'Product';
$model = new $m('search');
$model->unsetAttributes(); // clear any default values
if (isset($_GET[$m])) {
    $model->attributes = $_GET[$m];
}
if (Tak::getSupplier()) {
    $searchs = Setting::getStocks();
} elseif (Tak::getQuery('Setting')) {
    $searchs = Tak::getQuery('Setting');
} else {
    $searchs = array();
}
$typeid = isset($searchs['stocks_typeid']) ? $searchs['stocks_typeid'] : 0;
$warehouse_id = isset($searchs['stocks_warehouse_id']) ? $searchs['stocks_warehouse_id'] : 0;
$stocks_name = isset($searchs['stocks_name']) ? $searchs['stocks_name'] : '';

$typeid > 0 && $model->typeid = $typeid;
$warehouse_id > 0 && $model->warehouse_id = $warehouse_id;
$stocks_name != '' && $model->name = $stocks_name;

$warehouses = Warehouse::toSelects(Tk::g(array(
    'All',
    'Warehouse'
)));

$ihtmls = array();
?>
<div class="row-fluid">
  <div class="span12">
  <div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('库存') ?></h1>
  </div>  
  <div class="block-fluid clearfix">
  <?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-form',
    'type' => 'search',
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));
if ($typeid <= 0) {
?>
<span class="span2">
<?php
    $this->renderPartial('/category/select', array(
        'id' => sprintf("%s[typeid]", $model->mName) ,
        'value' => $model->typeid,
    ));
?>
</span>

<?php
} else {
    $html = sprintf("%s : %s ", $model->getAttributeLabel('typeid') , Category::getProductName($typeid));
    $ihtmls[] = JHtml::tag('span', $html, array(
        'class' => 'badge'
    ));
}
echo ' ';
if ($warehouse_id <= 0) {
    echo $form->dropDownList($model, 'warehouse_id', $warehouses);
} else {
    $html = sprintf("%s : %s", Tk::g('Warehouse') , $warehouses[$warehouse_id]);
    $ihtmls[] = JHtml::tag('span', $html, array(
        'class' => 'badge'
    ));
}
echo ' ';
if ($stocks_name == '') {
    echo $form->textFieldRow($model, 'name', array(
        'size' => 10,
        'maxlength' => 10
    ));
} else {
    $html = sprintf("%s : %s ", $model->getAttributeLabel('name') , $stocks_name);
    $ihtmls[] = JHtml::tag('span', $html, array(
        'class' => 'badge'
    ));
}
echo ' ';
if (count($ihtmls) < 3) {
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label' => Tk::g('Search')
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Tk::g('Reset') ,
        'htmlOptions' => array(
            'href' => Yii::app()->createUrl($this->route)
        )
    ));
}
echo ' ';
echo implode(' ', $ihtmls);
$this->endWidget();

$options = Tak::gredViewOptions(false);
$tags = $model->search();
$columns = array(
    array(
        'name' => 'name',
        'type' => 'raw',
        // 'value' => 'CHtml::link($data->name,array("viewProduct","id"=>$data->primaryKey))',
        // 'value' => '$data->name,array("viewProduct","id"=>$data->primaryKey)',
        
        
    ) ,
    array(
        'name' => 'typeid',
        'value' => 'Category::getProductName($data->typeid)',
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
    array(
        'name' => '数量',
        'type' => 'raw',
        'value' => 'Tak::tagNum($data->stock)',
    ) ,
    /*
    array(
        'name' => 'price',
        'value' => 'Tak::format_price($data->price)',
    ) ,
    
    array(
        'name' => '小计',
        'value' => 'Tak::format_price($data->total)',
    )
    */
);

$options['columns'] = $columns;
$options['dataProvider'] = $tags;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
    </div>
  </div>
</div>
