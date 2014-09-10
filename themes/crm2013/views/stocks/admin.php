<?php
/* @var $this StocksController */
/* @var $model Stocks */

$this->breadcrumbs=array(
	Tk::g($this->modelName) => array('admin'),
);
$items = Tak::getListMenu();
?>
<div class="row-fluid">
	<div class="span12">
	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g(array($this->modelName))?></h1>
	</div>	

	<div class="block-fluid clearfix">

<?php $this->renderPartial('_search',array('model'=>$model)); ?>

<?php 

$options = Tak::gredViewOptions(false);
$options['dataProvider'] = $model->with('iStocks')->search();
$columns = array(	
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>'CHtml::link($data->name,array("view","id"=>$data->iStocks->stocks))',
		)	
,		array(
			'name'=>'material',
			'type'=>'raw',
			'value'=>'$data->material',
		)
,		array(
			'name'=>'spec',
			'type'=>'raw',
			'value'=>'$data->spec',
		)				
,		array(
			'name'=>'stocks',
			'value'=>'$data->stock',
		)				
,		array(
			'name'=>'price',
			'value'=>'$data->price',
		)
,		array(
			'name'=>'小计',
			'type'=>'raw',
			'value'=>'$data->total',
		)

,		array(
			'header'=>'最后变更',
			'name'=>'modified_time',
			'value'=>'Tak::timetodate($data->iStocks->modified_time,4)',
            'htmlOptions'=>array('style'=>'width: 85px')
		)
	);
	$options['columns'] = $columns;
	$widget = $this->widget('bootstrap.widgets.TbGridView', $options); 
?>
		</div>
	</div>
</div>
