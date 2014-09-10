<?php
/* @var $this TestLogController */
/* @var $model TestLog */

$this->breadcrumbs=array(
	Tk::g('Test Logs')=>array('admin'),
	$model->primaryKey,
);
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'fromid',
		'manageid',
		'user_name',
		'qstring',
		array('name'=>'info','type'=>"raw"),
		array('name'=>'ip', 'value'=>Tak::Num2IP($model->ip),),
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
	),
)); ?>
