<?php
/* @var $this AdminLogController */
/* @var $model AdminLog */

$this->breadcrumbs=array(
	'Admin Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AdminLog', 'url'=>array('index')),
	array('label'=>'Manage AdminLog', 'url'=>array('admin')),
);
?>

<h1>Create AdminLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>