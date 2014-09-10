<?php
/* @var $this AdminLogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'操作日志',
);

?>

<?php $this->widget('bootstrap.widgets.TbListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
