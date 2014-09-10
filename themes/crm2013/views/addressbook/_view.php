<?php 
$items1 = array();
$items = array(
		'name',
		'groups.name',
		'position',
		'email',
		'phone',
		'address',
		'sex'=>array('name'=>'sex'
                ,'type'=>'raw',
                'value'=> TakType::getStatus('sex',$data->sex),),
	);

if (isset($isportion)) {
	$items1 = array(
		// 'longitude',
		// 'latitude',
		// 'location',
		'display'=>array('name'=>'display','type'=>'raw', 'value'=>TakType::getStatus('display',$data->display),),
		'note',
		'add_time' => array('name'=>'add_time', 'value'=>Tak::timetodate($data->add_time),),
		'modified_time'=>array('name'=>'modified_time', 'value'=>Tak::timetodate($data->modified_time),),
		);
	$items = array_merge_recursive($items, $items1);	
}
$this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$data,
	'attributes'=>$items
)); ?>