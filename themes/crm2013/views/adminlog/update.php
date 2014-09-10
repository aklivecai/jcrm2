<?php
/* @var $this AdminLogController */
/* @var $model AdminLog */

$this->breadcrumbs=array(
	'Admin Logs'=>array('index'),
	$model->itemid=>array('view','id'=>$model->itemid),
	'Update',
);

$this->menu=array(
	array('label'=>'List AdminLog', 'url'=>array('index')),
	array('label'=>'Create AdminLog', 'url'=>array('create')),
	array('label'=>'View AdminLog', 'url'=>array('view', 'id'=>$model->itemid)),
	array('label'=>'Manage AdminLog', 'url'=>array('admin')),
);
?>

<h1>Update AdminLog <?php echo $model->itemid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>