<?php
/* @var $this AddressBookController */
/* @var $model AddressBook */

$this->breadcrumbs=array(
	Tk::g('Address Books') => array('admin'),
	$model->name,
);

$items = Tak::getViewMenu($model->itemid);	
$_itemis = array(
	'---',
	array('label'=>Tk::g('More'), 'url'=>'#', 'icon'=>'list','itemOptions'=>array('data-geturl'=>$model->getLink(false,'gettop'),'class'=>'more-list'),'submenuOptions'=>array('class'=>'more-load-info'),'items'=>array(
    	array('label'=>'...', 'url'=>'#'),
	))
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
		<?php $this->renderPartial('_view',array(
		    'data'=>$model,
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