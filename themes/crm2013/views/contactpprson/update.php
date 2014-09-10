<?php
/* @var $this ContactpPrsonController */
/* @var $model ContactpPrson */

$this->breadcrumbs=array(
	Tk::g('Contactp Prsons')=>array('admin'),
	$model->itemid=>array('view','id'=>$model->itemid),
	Tk::g('Update'),
);
?>	
<?php $this->renderPartial('_form', array('model'=>$model)); ?>