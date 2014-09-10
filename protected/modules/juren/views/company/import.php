<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs=array(
	'平台会员' => array('admin'),
	Tk::g('Import'),
);
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'test-memeber-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'company'); ?>
		<?php echo $form->textField($model,'company',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'company'); ?>
	</div>
	
	<?php if($model->active_time>0):?>
	<div class="row">
		<?php echo $form->label($model,'active_time'); ?>
		<?php echo $form->textField($model,'active_time',array('size'=>15,'maxlength'=>10,'class'=>'date')); ?>
	</div>	
	<?php else :?>
	<div class="row">
		<?php echo $form->label($model,'user_name'); ?>
		<?php echo $form->textField($model,'user_name',array('size'=>25,'maxlength'=>60)); ?>
	</div>			
	<?php endif ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textArea($model,'note',array('cols'=>25,'rows'=>3,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Tk::g('添加') : Tk::g('Save')); ?>
	</div>
<?php echo $form->hiddenField($model,'itemid');?>
<?php $this->endWidget(); ?>

</div><!-- form -->