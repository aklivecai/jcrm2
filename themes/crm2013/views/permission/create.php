<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g($this->modelName)=>array('admin'),
	Tk::g('Create'),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>