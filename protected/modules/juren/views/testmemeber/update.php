<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs=array(
	Tk::g('Test Memebers')=>array('admin'),
	$model->primaryKey => array('view','id'=>$model->primaryKey),
	Tk::g('Update'),
);

$this->menu = array_merge_recursive($this->menu,
array(
	array('label'=>Tk::g('View'), 'url'=>array('view', 'id'=>$model->primaryKey)),
	array('label'=>Tk::g(array('View','Log')), 'url'=>array('testLog/admin', 'TestLog[formid]'=>$model->primaryKey)),
	array('label'=>Tk::g('Delete'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->primaryKey)),)
)
);
?>
<?php $this->renderPartial('_form', array('model'=>$model,'manages'=>$manages)); ?>