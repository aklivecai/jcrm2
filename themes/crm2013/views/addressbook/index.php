<?php
/* @var $this AddressBookController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Tk::g('Address Books'),
);
?>
<div class="row-fluid">
	<div class="span12">
	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('AddressBook')?></h1>                              
	</div>	
<div class="block-fluid clearfix">

<?php

$this->renderPartial('_search',array('model'=>$model,));

$listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
		Tak::getAdminPageCol(array(
			'template'=>'{preview}','buttons'=>array(
				'preview' => array(
	                'label'=>'',
	                 'url'=>'Yii::app()->controller->createUrl("views", array("id"=>$data->primaryKey))',
	                 'options'=>array('title'=>'浏览','class'=>'icon-eye-open'),
	                )
			)
		))
		,array(
			'name'=>'name',
			'type'=>'raw',
		)
		,array(
			'name'=>'telephone',
			'type'=>'raw',
            'sortable' => false,
		)
		,array(
			'name'=>'phone',
			'type'=>'raw',
            'sortable' => false,
		)
		,array(
			'name'=>'email',
			'type'=>'email',
            'sortable' => false,
		)
		,array(
			'name'=>'position',
			'type'=>'raw',
			'headerHtmlOptions'=>array('style'=>'width: 85px'),
             'filter' => false,
            'sortable' => false,
		)
		,array(
			'name' => 'groups_id',
			'type'=>'raw',
			'headerHtmlOptions'=>array('style'=>'width: 85px'),
			'value'=>'TakType::item("AddressGroups",$data->groups_id)',
			'filter'=>TakType::items('AddressGroups'), 
		)
	,
	);
	$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>
		</div>
	</div>
</div>