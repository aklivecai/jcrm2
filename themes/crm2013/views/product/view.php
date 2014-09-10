<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	Tk::g($model->sName) => array('admin'),
	$model->name,
);
	$items = Tak::getViewMenu($model->itemid);

$nps = $model->getNP(true);
if (count($nps)>0) {
	$_itemis[] = 
		array('label'=>Tk::g(array('More','Product')), 'url'=>'#', 'icon'=>'list','itemOptions'=>array('data-geturl'=>$model->getLink(false,'gettop'),'class'=>'more-list'),'submenuOptions'=>array('class'=>'more-load-info'),'items'=>array(
	    	array('label'=>'...', 'url'=>'#'),
		)
	);
   array_splice($_itemis,count($_itemis),0,Tak::getNP($nps));
}
 array_splice($items,count($items)-2,0,$_itemis);
?>
<div class="block-fluid">
	<div class="row-fluid">
		<div class="span10">
			<?php $this->renderPartial('_view',array('model'=>$model,)); ?>
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