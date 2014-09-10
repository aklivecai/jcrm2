<?php
/* @var $this EventsController */
/* @var $model Events */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php  $action = $model->isNewRecord?'Create':'Update';
 $items = Tak::getEditMenu($model->itemid,$model->isNewRecord);
?>
<div class="row-fluid">
<div class="span12">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'events-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo Tk::g(array('Events',$action));?></h1>
<?php 
$this->widget('application.components.MyMenu',array(
      'htmlOptions'=>array('class'=>'buttons'),
      'items'=> $items ,
));
?>      
</div>
<div class="block-fluid">
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<div class="row-form clearfix">
		<?php echo $form->dropDownListRow($model,'type',TakType::items('contact-type')); ?>
	</div>	
	<div class="row-form clearfix">
		<?php echo $form->dropDownListRow($model,'priority',TakType::items('priority')); ?>
	</div>	
	<div class="row-form clearfix">
		<?php echo $form->dropDownListRow($model,'event_status',TakType::items('event-status')); ?>
	</div>	
	<div class="row-form clearfix hide" >
		<?php echo $form->textFieldRow($model,'email',array('size'=>60,'maxlength'=>128)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'start_time',array('class'=>'type-date','size'=>10,'maxlength'=>10)); ?>
	</div>
	<!--
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'end_time',array('class'=>'type-date','size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'color',array('class'=>'color','size'=>15,'maxlength'=>15)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'text_color',array('class'=>'color','size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'location',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'url',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	-->
	<div class="row-form clearfix">
		<?php echo $form->radioButtonListRow($model,'display',Taktype::items('display'),array('class'=>'','template'=>'<label class="checkbox inline">{input}{label}</label>')); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textAreaRow($model,'note',array('size'=>60,'maxlength'=>255)); ?>
	</div>

</div>

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'submit', 'label'=>Tk::g($action))); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'reset', 'label'=>Tk::g('Reset'))); ?>
    
</div>

<?php $this->endWidget(); ?>
</div>
</div>