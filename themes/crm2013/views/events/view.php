<?php
/* @var $this EventsController */
/* @var $model Events */

$this->breadcrumbs=array(
	Tk::g('Events') => array('admin'),
	$model->itemid,
);
	$items = Tak::getViewMenu($model->itemid);
?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'subject',
		array('name'=>'type','type'=>'raw', 'value'=>TakType::getStatus('contact-type',$model->type),),
		array('name'=>'event_status','type'=>'raw', 'value'=>TakType::getStatus('event-status',$model->event_status),),
		array('name'=>'priority','type'=>'raw', 'value'=>TakType::getStatus('priority',$model->priority),),
		array('name'=>'start_time', 'value'=>Tak::timetodate($model->start_time),),
		/*
		array('name'=>'end_time', 'value'=>Tak::timetodate($model->end_time,6),),
		'email',
		'location',
		array('name'=>'url', 'type'=>'raw','value'=>$model->getNextUrl()),
		*/

		array('name'=>'display','type'=>'raw', 'value'=>TakType::getStatus('display',$model->display),),
		'note',
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