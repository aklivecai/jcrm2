<?php

$items = array(
			array(
				'label'=>Rights::t('core', 'Assignments'),
				'url'=>array('assignment/view'),
				'itemOptions'=>array('class'=>'item-assignments'),
			),
	);
if (Tak::getAdmin()) {
		array_push($items,array(
				'label'=>Rights::t('core', 'Permissions'),
				'url'=>array('authItem/permissions'),
				'itemOptions'=>array('class'=>'item-permissions'),
			),
			array(
				'label'=>Rights::t('core', 'Roles'),
				'url'=>array('authItem/roles'),
				'itemOptions'=>array('class'=>'item-roles'),
			),
			array(
				'label'=>Rights::t('core', 'Tasks'),
				'url'=>array('authItem/tasks'),
				'itemOptions'=>array('class'=>'item-tasks'),
			),
			array(
				'label'=>Rights::t('core', 'Operations'),
				'url'=>array('authItem/operations'),
				'itemOptions'=>array('class'=>'item-operations'),
			)
	);
}
array_push($items,array(
		'label'=>Tk::g(array('Manages','Admin')),
		'url'=>array('/manage/admin'),
		'itemOptions'=>array('class'=>'item-permissions'),
	)
);

$items[] =
		array(
				'label'=>Tk::g('View Roles'),
				'url'=>array('assignment/viewRoles'),
				'itemOptions'=>array('class'=>'item-assignments'),
			); 


$this->widget('bootstrap.widgets.TbButtonGroup', array('buttons'=>$items));
?>