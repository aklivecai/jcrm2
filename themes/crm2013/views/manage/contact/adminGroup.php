<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->breadcrumbs=array(
	Tk::g('Contacts')=>array('admin'),
	Tk::g('Admin'),
);
$items = Tak::getListMenu();
?>

<div class="row-fluid">
	<div class="span12">

	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('Contact')?></h1>   
		<?php 
		$this->widget('application.components.MyMenu',array(
		      'htmlOptions'=>array('class'=>'buttons'),
		      'items'=> $items ,
		));
		?>                                       
	</div>	

	<div class="block-fluid clearfix">
<?php 
	// $this->renderPartial('_search',array('model'=>$model,)); 
?>

<?php $widget = $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'id' => 'list-grid',
	'dataProvider'=>$model->group()->search(),
	'template'=>"{items}",
	'enableHistory'=>true,
    'loadingCssClass' => 'grid-view-loading',
    'summaryCssClass' => 'dataTables_info',
    'pagerCssClass' => 'pagination dataTables_paginate',
    'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
    'ajaxUpdate'=>true,    //禁用AJAX
    'enableSorting'=>true,
    'summaryText' => '<span>共{pages}页</span> <span>当前:{page}页</span> <span>总数:{count}</span> ',
	'pager'=>array(
		'header'=>'',
		'maxButtonCount' => '5',
		'hiddenPageCssClass' => 'disabled'
		,'selectedPageCssClass' => 'active disabled'
		,'htmlOptions'=>array('class'=>'')
	),
	'columns'=>array(
	Tak::getAdminPageCol(),
		array(
			'name'=>'clienteleid',
			'type'=>'raw',
			'value'=>'$data->iClientele->clientele_name'
		)		
		,array(
			'name'=>'prsonid',
			'type'=>'raw',
			'value'=>'$data->iContactpPrson->nicename'
		)
		,array(
			'name'=>'contact_time',
			'value'=>'Tak::timetodate($data->contact_time,6)',
		)
		,array(
			'name'=>'next_contact_time',
			'value'=>'Tak::timetodate($data->next_contact_time,6)',
		)
		,array(
			'name' => 'stage',
			'htmlOptions'=>array('style'=>'width: 80px'),
			'value'=>'TakType::getStatus("contact-stage",$data->stage)',
			'type'=>'raw',
		)	
	),
)); 
?>
		</div>
	</div>
</div>
