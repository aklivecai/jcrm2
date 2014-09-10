<?php
/* @var $this OrderProductController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Order Products',
);

$this->menu=array(
	array('label'=>'Create OrderProduct', 'url'=>array('create')),
	array('label'=>'Manage OrderProduct', 'url'=>array('admin')),
);
?>

<h1>Order Products</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
