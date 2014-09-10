<?php
/* @var $this AddressBookController */
/* @var $model AddressBook */

$this->breadcrumbs=array(
	Tk::g('Address Books') => array('index'),
	$model->name,
);

$geturl = $this->createUrl('gettop',array('id'=>$model->primaryKey,'view'=>'views'));

$_itemis = array(
	'---',
	array('label'=>Tk::g('More'), 'url'=>'#', 'icon'=>'list','itemOptions'=>array('data-geturl'=>$geturl,'class'=>'more-list'),'submenuOptions'=>array('class'=>'more-load-info'),'items'=>array(
    	array('label'=>'...', 'url'=>'#'),
	))
);

$nps = $model->getNP(true);
if (count($nps)>0) {
   array_splice($_itemis,count($_itemis),0,Tak::getNP($nps,'views'));
}

?>

<div class="block-fluid">
	<div class="row-fluid">
		<div class="span10">
			<?php $this->renderPartial('_view',array(
		    	'data'=>$model,
		    	'isportion'=>true
			)); ?>
		</div>
		<div class="span2">
		<?php $this->widget('bootstrap.widgets.TbMenu', array(
		    'type'=>'list',
		    'items'=> $_itemis,
		    )
		); 
		?>
		</div>		
	</div>
</div>