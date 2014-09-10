<?php $this->breadcrumbs = array(
    Rights::t('core', 'Rights').''=>Rights::getBaseUrl(),
    Rights::t('core', 'Assignments'),
); ?>
<div class="row-fluid">

<div class="page-header">
<h1><?php echo Rights::t('core', 'Assignments'); ?>
<small><?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?></small></h1>
</div>
<div class="row-fluid" id="assignments">
<div class="head clearfix">
    <i class="isw-documents"></i> <h1><?php echo Tk::g(array('Update'));?></h1>
<?php
 $items = array(
  array(
      'icon' =>'isw-zoom',
      'url' => array('/manage/admin'),
      'label'=>Tk::g(array('Return','Manage','Admin')),
    )
    ,array(
      'icon' =>'isw-refresh',
      'url' => Yii::app()->request->url,
      'label'=>Tk::g('Refresh'),
    )        
);

$this->widget('application.components.MyMenu',array(
      'htmlOptions'=>array('class'=>'buttons'),
      'items'=> $items ,
));
?>  
</div>
<div class="block-fluid clearfix">

<?php /** @var BootActiveForm $form */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'searchform',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well'),
    // 'action' => Yii::app()->createUrl($this->route),
    
)); ?>

<?php 
    echo $form->dropDownList($module, 'itemname', $assignSelectOptions); 
?>
<?php 
    echo $form->textFieldRow($module, 'username'); 
?>

<?php 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); 
?>
<?php $this->endWidget(); ?>   

	<?php  $widget = $this->widget('bootstrap.widgets.TbGridView', array(
	    'dataProvider'=>$dataProvider,
	    'template'=>"{items}\n{pager}",
	    'emptyText'=>Rights::t('core', 'No users found.'),
	    'htmlOptions'=>array('class'=>'grid-view assignment-table'),
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>Rights::t('core', 'Name'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column'),
    			'value'=>'$data->getAssignmentNameLink()." - ".$data->user_nicename',
    		),
    		array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Roles'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'role-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Tasks'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'task-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Operations'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'operation-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
    		),
	    )
	)); ?>
</div>
</div>
</div>