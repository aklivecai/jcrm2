<?php
/* @var $this OrderFilesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Order Files',
);

$this->menu=array(
	array('label'=>'Create OrderFiles', 'url'=>array('create')),
	array('label'=>'Manage OrderFiles', 'url'=>array('admin')),
);
?>

<h1>Order Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
