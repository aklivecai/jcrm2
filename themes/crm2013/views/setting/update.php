<?php
/* @var $this SettingController */
/* @var $model Setting */

$this->breadcrumbs=array(
	'Settings'=>array('index'),
	$model->itemid=>array('view','id'=>$model->itemid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Setting', 'url'=>array('index')),
	array('label'=>'Create Setting', 'url'=>array('create')),
	array('label'=>'View Setting', 'url'=>array('view', 'id'=>$model->itemid)),
	array('label'=>'Manage Setting', 'url'=>array('admin')),
);
?>

<h1>Update Setting <?php echo $model->itemid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>