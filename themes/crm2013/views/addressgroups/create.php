<?php
/* @var $this AddressGroupsController */
/* @var $model AddressGroups */

$this->breadcrumbs=array(
	Tk::g('Address Groups')=>array('admin'),
	Tk::g('Create'),
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>