<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g(array('Seas','Clienteles')) => array('seas'),
);
?>

<div class="row-fluid">
	<div class="span12">
	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g(array('Seas','Clienteles'))?></h1>
	</div>
		<div class="block-fluid clearfix">
<?php $this->renderPartial('/clienteles/_search',array('model'=>$model,)); ?>

<?php
$listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
		array(
			 'class'=>'bootstrap.widgets.TbButtonColumn'
			  , 'template'=>'{move} , {show}'
			  , 'headerHtmlOptions'=>array('width'=>'85')
		              ,'buttons'=>array(
		                    'move' => array
		                    (
		                        'label'=>'捞起来',
		                         'url'=>'Yii::app()->controller->createUrl("getSeas", array("id"=>$data->primaryKey))',
		                         'options'=>array('title'=>Tk::g(array('Move','Clientele')),),
		                    ),
		                    'show' => array
		                    (
		                        'label'=>'查看',
		                         'url'=>'Yii::app()->controller->createUrl("showSeas", array("id"=>$data->primaryKey))',
		                         'options'=>array('title'=>Tk::g(array('Move','Clientele')),),
		                    ),
		              )
		),	
		array(
			'name'=>'clientele_name',
		),
		array(
			'name'=>'manageid',
			'type'=>'raw',
			'value' => '$data->iManage->user_nicename',
			'headerHtmlOptions'=>array('style'=>'width: 65px'),
		),
		array(
			'name' => 'industry',
			// 'header'=>'类型',
			'htmlOptions'=>array('style'=>'width: 80px'),
			'value'=>'TakType::getStatus("industry",$data->industry)',
			'type'=>'raw', 
			'headerHtmlOptions'=>array('style'=>'width: 80px'),
		),	
		array(
			'name'=>'address',
			'type'=>'raw',
            	'sortable' => false,
		),
		array(
			'header'=>'最后联系',
			'name'=>'last_time',
			'value'=>'Tak::timetodate($data->last_time,6)',
			'headerHtmlOptions'=>array('style'=>'width: 80px'),
		),		
		array(
			'name'=>'add_time',
			'value'=>'Tak::timetodate($data->add_time,6)',
			'headerHtmlOptions'=>array('style'=>'width: 80px'),
		),
	);
	$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>
		</div>
	</div>
</div>


<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
	$(document).on('k-over','#myModal',function(){
		$.fn.yiiGridView.update('list-grid');
	})
});
/*]]>*/
</script>