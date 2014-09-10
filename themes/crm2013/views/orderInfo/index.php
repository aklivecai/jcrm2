<?php
/* @var $this OrderInfoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Order Infos',
);

$this->menu=array(
	array('label'=>'Create OrderInfo', 'url'=>array('create')),
	array('label'=>'Manage OrderInfo', 'url'=>array('admin')),
);
?>

<h1>Order Infos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
