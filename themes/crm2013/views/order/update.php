<?php
/* @var $this OrderController */
/* @var $model Order */

$this->breadcrumbs=array(
	Tk::g($model->sName)=>array('admin'),
	$model->itemid=>array('view','id'=>$model->itemid),
	Tk::g('Update'),
);
?>	
<?php $this->renderPartial('_form', array('model'=>$model)); ?>