<?php
/* @var $this ClienteleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Clienteles',
);

$this->menu=array(
	array('label'=>'Create Clientele', 'url'=>array('create')),
	array('label'=>'Manage Clientele', 'url'=>array('admin')),
);
?>

<h1>Clienteles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
