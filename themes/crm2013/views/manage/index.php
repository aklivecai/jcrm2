<?php
/* @var $this ManageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Manages',editpl
);

$this->menu=array(
	array('label'=>'Create Manage', 'url'=>array('create')),
	array('label'=>'Manage Manage', 'url'=>array('admin')),
);
?>

<h1>Manages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
