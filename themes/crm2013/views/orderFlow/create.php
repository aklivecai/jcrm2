<?php
/* @var $this OrderFlowController */
/* @var $model OrderFlow */

$this->breadcrumbs=array(
	Tk::g($model->sName)=>array('admin'),
	Tk::g('Create'),
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>