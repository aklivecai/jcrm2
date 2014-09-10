<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	Tk::g($model->sName) => array('admin'),
	Tk::g('Update'),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>