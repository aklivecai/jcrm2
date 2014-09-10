<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);
$items = Tak::getListMenu();
Tak::regScript('tak', 'isprintf = true;', CClientScript::POS_END);
?>
<div class="row-fluid">
	<div class="span12">

	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g($model->sName) ?></h1>   
		<?php
$this->widget('application.components.MyMenu', array(
    'htmlOptions' => array(
        'class' => 'buttons'
    ) ,
    'items' => $items,
));
?>                                    
	</div>	

	<div class="block-fluid clearfix">
<?php
$this->renderPartial("type", array(
    'model' => $model,
));
$this->renderPartial("_search", array(
    'model' => $model,
));

$options = Tak::gredViewOptions(false);
$options['dataProvider'] = $model->search();
$columns = array(
    Tak::getAdminPageCol() ,
    'name',
    array(
        'name' => 'typeid',
        'type' => 'raw',
        'value' => 'Category::getProductName($data->typeid," / ")',
    ) ,
    array(
        'name' => 'material',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'spec',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'color',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'price',
        'type' => 'raw',
        'value' => 'Tak::format_price($data->price)',
        'htmlOptions' => array(
            'style' => 'width:120px;'
        ) ,
    )
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
		</div>
	</div>
</div>
