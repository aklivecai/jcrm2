<?php
/* @var $this AdminLogController */
/* @var $model AdminLog */

$this->breadcrumbs=array(
	Tk::g('AdminLog')=>array('admin'),
	$model->itemid,
);

$this->menu=array(
	array('label'=>'List AdminLog', 'url'=>array('index')),
	array('label'=>'Create AdminLog', 'url'=>array('create')),
	array('label'=>'Update AdminLog', 'url'=>array('update', 'id'=>$model->itemid)),
	array('label'=>'Delete AdminLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->itemid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AdminLog', 'url'=>array('admin')),
);
?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'user_name',
		'qstring',
		'info',
		array('name'=>'ip', 'value'=>Tak::Num2IP($model->ip),),
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
	),
)); ?>
