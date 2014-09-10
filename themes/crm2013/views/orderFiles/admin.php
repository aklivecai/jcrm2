<?php
/* @var $this OrderFilesController */
/* @var $model OrderFiles */

$this->breadcrumbs=array(
	Tk::g($model->sName)=>array('admin'),
	Tk::g('Admin'),
);
$items = Tak::getListMenu();
?>
<div class="row-fluid">
	<div class="span12">

	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('OrderFiles')?></h1>   
		<ul class="buttons">
		    <li>
		        <a href="#" class="isw-settings"></a>
<?php 
				$this->widget('application.components.MyMenu',array(
				      'htmlOptions'=>array('class'=>'dd-list'),
				      'items'=> $items ,
				));
			?>      
		    </li>
		</ul>                                    
	</div>	

	<div class="block-fluid clearfix">
<?php $this->renderPartial('//_search',array('model'=>$model,)); ?>
<?php
 $listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
		array(
			 'class'=>'bootstrap.widgets.TbButtonColumn'
			  , 'template'=>'{view} | {update}'
			  ,'header' => CHtml::dropDownList('pageSize'
					,Yii::app()->user->getState('pageSize')
					,TakType::items('pageSize')
					,array(
						'onchange'=>"$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})", 
					)
			  )
			  ,'htmlOptions'=>array('style'=>'width: 85px')
		),		
		'action_id'
,		'file_type'
,		'file_name'
,		'file_path'
	);

$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>
		</div>
	</div>
</div>
