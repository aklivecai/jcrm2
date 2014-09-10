<?php
/* @var $this TestMemeberController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Test Memebers',
);

$this->menu=array(
	array('label'=>'Create TestMemeber', 'url'=>array('create')),
	array('label'=>'Manage TestMemeber', 'url'=>array('admin')),
);
?>

<h1>Test Memebers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
