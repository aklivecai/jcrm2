<?php
/* @var $this AddressBookController */
/* @var $model AddressBook */

$this->breadcrumbs=array(
	Tk::g('Address Books')=>array('admin'),
	Tk::g('Admin'),
);
$items = Tak::getListMenu();
?>
<div class="row-fluid">
	<div class="span12">

	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('AddressBook')?></h1>   
		<?php 
		$this->widget('application.components.MyMenu',array(
		      'htmlOptions'=>array('class'=>'buttons'),
		      'items'=> $items ,
		));
		?>                                  
	</div>	

<div class="block-fluid clearfix">
<?php $this->renderPartial('//_search',array('model'=>$model,)); ?>
<?php 
	
	$this->renderPartial('_search',array('model'=>$model,));

$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->search();
$columns = array(	
		array(
			'name'=>'name',
			'type'=>'raw',
			'headerHtmlOptions'=>array('style'=>'width: 85px'),
			'value'=>'$data->getHtmlLink()',
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
	$columns = array_merge_recursive(array($options['columns']),$columns);
	$options['columns'] = $columns;
	$widget = $this->widget('bootstrap.widgets.TbGridView', $options); 
?>
		</div>
	</div>
</div>
