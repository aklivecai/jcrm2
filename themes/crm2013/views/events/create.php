<?php
/* @var $this EventsController */
/* @var $model Events */

$this->breadcrumbs=array(
	Tk::g('Events')=>array('admin'),
	Tk::g('Create'),
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>