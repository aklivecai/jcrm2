<?php
/* @var $this AddressGroupsController */
/* @var $model AddressGroups */

$this->breadcrumbs=array(
	Tk::g('Address Groups')=>array('admin'),
	$model->name=>array('view','id'=>$model->address_groups_id),
	Tk::g('Update'),
);
?>	
<?php $this->renderPartial('_form', array('model'=>$model)); ?>