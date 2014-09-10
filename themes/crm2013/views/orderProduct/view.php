<?php
/* @var $this OrderProductController */
/* @var $model OrderProduct */

$this->breadcrumbs=array(
	Tk::g($model->sName) => array('admin'),
	$model->name,
);
	$items = Tak::getViewMenu($model->itemid);
?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'order_id',
		'name',
		'model',
		'standard',
		'color',
		'unit',
		'amount',
		'price',
		'sum',
		'note',
	),
)); ?>
</div>
<div class="span2">
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list',
    'items'=> $items,
    )
); 
?>
</div>
</div>
</div>