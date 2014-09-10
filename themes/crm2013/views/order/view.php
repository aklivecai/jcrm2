<?php
/* @var $this OrderController */
/* @var $model Order */

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
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time),6),
		'total',
		array('name'=>'status','type'=>'raw', 'value'=>TakType::getStatus('status',$model->status),),
		array('name'=>'pay_time', 'value'=>Tak::timetodate($model->pay_time),6),
		array('name'=>'delivery_time', 'value'=>Tak::timetodate($model->delivery_time),6),
		array('name'=>'u_time', 'value'=>Tak::timetodate($model->u_time),6),
		'invoice_number',
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