<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g('Clienteles') => array('index'),
	Tk::g('Admin'),
);
?>

<div class="row-fluid">
	<div class="span12">
	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('Clienteles')?></h1>
	</div>
		<div class="block-fluid clearfix">
<?php $this->renderPartial('//_search',array('model'=>$model,)); ?>
<?php $this->renderPartial('_search',array('model'=>$model,)); ?>

<?php
$listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
		array(
			 'class'=>'bootstrap.widgets.TbButtonColumn'
			  , 'template'=>' {view} | {move} '
			  ,'header' => CHtml::dropDownList('pageSize'
					,Yii::app()->user->getState('pageSize')
					,TakType::items('pageSize')
					,array(
						'onchange'=>"$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})", 
					)
			  )
		              ,'buttons'=>array(
		                    'move' => array
		                    (
		                        'label'=>'',
		                         'url'=>'Yii::app()->controller->createUrl("move", array("id"=>$data->primaryKey))',
		                         'options'=>array('title'=>Tk::g(array('Move','Clientele')),'class'=>'icon-share-alt data-preview'),
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