<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */

$this->breadcrumbs=array(
	Tk::g($model->sName) => array('admin'),
	$model->itemid,
);
	$items = Tak::getViewMenu($model->itemid);
?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array('name'=>'date_time', 'value'=>Tak::timetodate($model->date_time),6),
		'detype',
		'pay_type',
		'earnest',
		'few_day',
		'delivery_before',
		'remaining_day',
		'packing',
		'taxes',
		'convey',
		'area',
		'address',
		'people',
		'tel',
		'phone',
		'purchasconsign',
		'contactphone',
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