<?php
/* @var $this MovingsController */
/* @var $model Movings */

$this->breadcrumbs=array(
	Tk::g($model->sName) => array('admin'),
	Tk::g('Entering'),
);

?>
<?php $this->renderPartial('//movings/_form', array('model'=>$model)); ?>