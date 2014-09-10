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
		?>
	</div><div class="block-fluid clearfix">
<?php 

$this->renderPartial('/_search',array('model'=>$model,));
$this->renderPartial('_search',array('model'=>$model,));

$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->search();

$columns = array(
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
	);
$columns = array_merge_recursive(array($options['columns']),$columns);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options); 
?>
		</div>
	</div>
</div>