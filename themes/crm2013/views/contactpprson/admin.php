<?php
/* @var $this ContactpPrsonController */
/* @var $model ContactpPrson */

$this->breadcrumbs=array(
	Tk::g('Contactp Prsons')=>array('admin'),
	Tk::g('Admin'),
);
$items = Tak::getListMenu();
?>
<div class="row-fluid">
	<div class="span12">

	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('ContactpPrson')?></h1>   
		<?php 
		$this->widget('application.components.MyMenu',array(
		      'htmlOptions'=>array('class'=>'buttons'),
		      'items'=> $items ,
		));
		?>                                       
	</div>	

	<div class="block-fluid clearfix">
<?php $this->renderPartial('//_search',array('model'=>$model,)); ?>
<?php $this->renderPartial('_search',array('model'=>$model)); ?>
<?php 
$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->search();
$columns = array(	
		array(
			'name'=>'nicename',
			'type'=>'raw',
			'headerHtmlOptions'=>array('style'=>'width: 80px'),
		)
		,array(
			'name'=>'clienteleid',
			'type'=>'raw',
			'value'=>'$data->iClientele?$data->iClientele->clientele_name:""',
		)
		,array(
			'name'=>'address',
			'type'=>'raw',
            'sortable' => false,
		)
		,array(
			'name'=>'mobile',
			'type'=>'raw',
			'headerHtmlOptions'=>array('style'=>'width: 85px'),
		)
		,array(
			'name'=>'last_time',
			'type'=>'raw',
			'value'=>'Tak::timetodate($data->last_time,4)',
			'headerHtmlOptions'=>array('style'=>'width: 85px'),
		)	
	);
	$columns = array_merge_recursive(array($options['columns']),$columns);
	$options['columns'] = $columns;
	$widget = $this->widget('bootstrap.widgets.TbGridView', $options); 
?>
		</div>
	</div>
</div>
