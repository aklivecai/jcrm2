<?php
/* @var $this ContactpPrsonController */
/* @var $model ContactpPrson */

$this->breadcrumbs=array(
	Tk::g('Contactp Prsons')=>array('admin'),
	Tk::g('Create'),
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>