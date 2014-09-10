<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g($this->getType().' Category')=> $this->cateUrl,
	Tk::g('Create'),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>