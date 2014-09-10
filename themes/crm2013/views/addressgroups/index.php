<?php
/* @var $this AddressGroupsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Address Groups',
);

$this->menu=array(
	array('label'=>'Create AddressGroups', 'url'=>array('create')),
	array('label'=>'Manage AddressGroups', 'url'=>array('admin')),
);
?>

<h1>Address Groups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
