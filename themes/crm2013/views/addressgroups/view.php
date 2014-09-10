<?php
/* @var $this AddressGroupsController */
/* @var $model AddressGroups */

$this->breadcrumbs=array(
	Tk::g('Address Groups') => array('admin'),
	$model->name,
);
	$items = Tak::getViewMenu($model->address_groups_id);

 	$items[] = array('label'=>Tk::g(array('View','Address Book')), 'icon'=>'eye-open','url'=>$this->createUrl('addressBook/admin/',array('AddressBook[groups_id]='=>$model->address_groups_id)),
	);
?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		array('name'=>'display','type'=>'raw', 'value'=>TakType::getStatus('display',$model->display),),
		'note',
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
		array('name'=>'modified_time', 'value'=>Tak::timetodate($model->modified_time,6),),
	),
)); ?>
</div>
<div class="span2">
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list',
    'items'=> $items,
    )
); 
?>
</div>
</div>
</div>