<?php
/* @var $this ManageController */
/* @var $model Manage */

$this->breadcrumbs=array(
	Tk::g('Manages')=>array('admin'),
	Tk::g('Update'),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>