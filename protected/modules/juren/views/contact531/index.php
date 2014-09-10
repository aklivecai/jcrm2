<?php
/* @var $this Contact531Controller */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contact531s',
);

$this->menu=array(
	array('label'=>'Create Contact531', 'url'=>array('create')),
	array('label'=>'Manage Contact531', 'url'=>array('admin')),
);
?>

<h1>Contact531s</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
