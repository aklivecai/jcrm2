<?php
/* @var $this TestLogController */
/* @var $model TestLog */

$this->breadcrumbs=array(
	Tk::g('Test Logs') => array('admin'),
	Tk::g('Admin'),
);
?>
<?php echo CHtml::link(Tk::g('Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'list-grid',
	'dataProvider' => $model->search(),
	'ajaxUpdate' => false,
	'enableHistory'=>false,
	'columns'=>array(	
		array(
			'class'=>'CButtonColumn',
			'template' => '{view}', 
            'header' => CHtml::dropDownList('pageSize'
                    ,Yii::app()->user->getState('pageSize')
                    ,TakType::items('pageSize')
                    ,array(
                        'onchange'=>"$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})", 
                    )   
              )   			
		),
		array(
			'name'=>'fromid',
			'type'=>'raw',
			'value' => 'CHtml::link($data->fromid,array("testMemeber/view","id"=>$data->fromid))'
		),
		'user_name',
		array('name'=>'info','type'=>"raw"),
		array(
			'name'=>'ip',
			'value'=>'Tak::Num2IP($data->ip)',
            'headerHtmlOptions' => array('style'=>'width:85px;')
		),	
		array(
			'name'=>'add_time',
			'value'=>'Tak::timetodate($data->add_time,5)',
            'headerHtmlOptions' => array('style'=>'width:120px;')
		),		
	),
)); ?>
