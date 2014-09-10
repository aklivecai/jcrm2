<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */

$this->breadcrumbs=array(
	Tk::g($model->sName)=>array('admin'),
	Tk::g('Create'),
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>