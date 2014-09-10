<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'nicename',
		array('name'=>'sex','type'=>'raw', 'value'=>TakType::getStatus('sex',$model->sex),),
		'department',
		'position',
		array('name'=>'email','type'=>'email'),
		'phone',
		'mobile',
		'fax',
		'qq',
		'address',
		array('name'=>'last_time', 'value'=>Tak::timetodate($model->last_time,6),),
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
		array('name'=>'modified_time', 'value'=>Tak::timetodate($model->modified_time,6),),
		'note',
	),
)); ?>