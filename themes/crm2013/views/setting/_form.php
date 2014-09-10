<?php
/* @var $this SettingController */
/* @var $model Setting */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'setting-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'itemid'); ?>
		<?php echo $form->textField($model,'itemid',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'itemid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'manageid'); ?>
		<?php echo $form->textField($model,'manageid',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'manageid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_key'); ?>
		<?php echo $form->textField($model,'item_key',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'item_key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_value'); ?>
		<?php echo $form->textArea($model,'item_value',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'item_value'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->