<?php
/* @var $this OrderFilesController */
/* @var $model OrderFiles */

$this->breadcrumbs=array(
	Tk::g($model->sName)=>array('admin'),
	$model->itemid=>array('view','id'=>$model->itemid),
	Tk::g('Update'),
);
?>	
<?php $this->renderPartial('_form', array('model'=>$model)); ?>