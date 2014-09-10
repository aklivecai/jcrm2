<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g('AdminLog')=>array('admin'),
	Tk::g('Admin'),
);
?>

<div class="row-fluid">
	<div class="span12">
	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('AdminLog')?></h1>                                     
	</div>
		<div class="block-fluid clearfix">
<?php $widget = $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'id' => 'list-grid',
	'dataProvider'=>$model->search(),
	'template'=>"{items}",
	'enableHistory'=>true,
    'loadingCssClass' => 'grid-view-loading',
    'summaryCssClass' => 'dataTables_info',
    'pagerCssClass' => 'pagination dataTables_paginate',
    'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
    'ajaxUpdate'=>true,    //禁用AJAX
    'enableSorting'=>true,
    'summaryText' => '<span>共{pages}页</span> <span>当前:{page}页</span> <span>总数:{count}</span> ',
	'filter'=>$model,
	'pager'=>array(
		'header'=>'',
		'maxButtonCount' => '5',
		'hiddenPageCssClass' => 'disabled'
		,'selectedPageCssClass' => 'active disabled'
		,'htmlOptions'=>array('class'=>'')
	),
	'columns'=>array(	
		array(		
			'name'=>'user_name',
			'type'=>'raw',
            	'headerHtmlOptions'=>array('style'=>'width: 180px'),
)
,		array(
			'name'=>'info',
			'type'=>'raw',
		)
,		array(
			'name'=>'ip',
			'type'=>'raw',
			 'value' => 'Tak::Num2IP($data->ip)',
            	'filter' => false,
            	'headerHtmlOptions'=>array('style'=>'width: 85px'),
		)	
,		array(
			'name'=>'add_time',
			'value'=>'Tak::timetodate($data->add_time,5)',
            	'filter' => false,
            	'headerHtmlOptions'=>array('style'=>'width: 120px'),
		),		
	),
)); 
?>
		</div>
	</div>
</div>
