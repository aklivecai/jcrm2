<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs=array(
	Tk::g('Contact531')=>array('admin'),
	$model->primaryKey => array('view','id'=>$model->primaryKey),
	Tk::g('Update'),
);

$this->menu[]  = array('label'=>Tk::g('Delete'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->primaryKey)));
?>
<?php $this->renderPartial('_form', array('model'=>$model,'manages'=>$manages)); ?>