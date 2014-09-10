<?php
/* @var $this MovingsController */
/* @var $model Movings */

$this->breadcrumbs=array(
	Tk::g($model->sName) => array('admin'),
	$model->itemid=>array('view','id'=>$model->itemid),
	Tk::g('Update'),
);
?>	
<?php $this->renderPartial('//movings/_form', array('model'=>$model)); ?>