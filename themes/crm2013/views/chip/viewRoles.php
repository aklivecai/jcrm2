<?php
/* @var $this StocksController */
/* @var $model Stocks */
$this->breadcrumbs=array(
	Rights::t('core', 'Rights')=> Rights::getBaseUrl(),
	Tk::g('View Roles'),
);
?>
<div class="">
<?php 

foreach ($tags as $key => $value) {
	if ($key=='Admin') {
		$_content = '<h1>拥有网站所有的权限操作!</h1>';
	}else{
		$_content = $this->widget('bootstrap.widgets.TbGridView', array(
					'type'=>'striped bordered condensed',
					'dataProvider'=>$value['data'],
					'template'=>'{items}',
					'hideHeader'=>false,
					'ajaxUpdate'=>false,
					'htmlOptions'=>array('class'=>'grid-view parent-table mini'),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Rights::t('core', 'Name'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLinkTak()',
    					),
    					array(
    						'header'=>Rights::t('core', 'Type'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
					)
				),true);	
	}
	
$tags[$key]['content'] = $_content;
unset($tags[$key]['data']);

}

$isactive = isset($_GET['tab'])&&isset($_GET['tab'])?$_GET['tab']:false;

if ($isactive&&$tags[$isactive]) {
	$tags[$isactive]['active'] = true; 
}else{
	$tags['Authenticated']['active'] = true; 
}

$this->widget('bootstrap.widgets.TbTabs', array(
	'id'=>'myTab',
    'type'=>'tabs', // 'tabs' or 'pills'
    'placement'=>'above', // 'above', 'right', 'below' or 'left'
    'tabs' => $tags,
)); 
?>

</div>

<script>
  $(function () {
  	$('a[href=#Authenticated]').on('click',function(event){
  		event.stopPropagation();
  		$('#myTab .nav-tabs a:last').tab('show');
  	})
  })
</script>