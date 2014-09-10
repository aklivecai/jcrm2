<?php
/* @var $this ContactpPrsonController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contactp Prsons',
);

$this->menu=array(
	array('label'=>'Create ContactpPrson', 'url'=>array('create')),
	array('label'=>'Manage ContactpPrson', 'url'=>array('admin')),
);
?>

<h1>Contactp Prsons</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
