<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->breadcrumbs=array(
	Tk::g('Contacts') => array('adminGroup'),
	$model->itemid,
);
	$items = Tak::getViewMenu($model->itemid);

	$items['Create']['url'] = array('create','Contact[clienteleid]'=>$model->clienteleid,'Contact[prsonid]'=>$model->prsonid);
$_itemis = array(
	'---',
	'viewCompay' => array('label'=>Tk::g(array('View','Clientele')),'url'=>array('Clientele/view','id'=>$model->clienteleid)),
	'viewContactpPrson' => array('label'=>Tk::g(array('View','Contactp Prson')),'url'=>array('ContactpPrson/view','id'=>$model->prsonid)),
		array('label'=>'列表', 'url'=>'#', 'icon'=>'list','items'=>array(
	    	array('label'=>'...', 'url'=>'#'),
		)
	)
);

$nps = $model->getNP(true);
if (count($nps)>0) {
   array_splice($_itemis,count($_itemis),0,Tak::getNP($nps));
}

 array_splice($items,count($items)-2,0,$_itemis);

?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iClientele.clientele_name',
		'iContactpPrson.nicename',
		array('name'=>'type','type'=>'raw', 'value'=>TakType::getStatus('contact-type',$model->type),),
		array('name'=>'stage','type'=>'raw', 'value'=>TakType::getStatus('contact-stage',$model->stage),),
		array('name'=>'contact_time', 'value'=>Tak::timetodate($model->contact_time,6),),
		array('name'=>'next_contact_time', 'value'=>Tak::timetodate($model->next_contact_time,6),),
		'next_subject',
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
		array('name'=>'modified_time', 'value'=>Tak::timetodate($model->modified_time,6),),
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