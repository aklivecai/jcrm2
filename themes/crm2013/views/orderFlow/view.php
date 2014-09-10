<?php
/* @var $this OrderFlowController */
/* @var $model OrderFlow */

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
		array('name'=>'status','type'=>'raw', 'value'=>TakType::getStatus('status',$model->status),),
		'action_user',
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time),6),
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