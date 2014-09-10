<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'test-memeber-form',
    'enableAjaxValidation' => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'module'); ?>
		<?php echo $form->textField($model, 'module', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
		<?php echo $form->error($model, 'module'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'listorder'); ?>
		<?php echo $form->numberField($model, 'listorder'); ?>
		<?php echo $form->error($model, 'listorder'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'status'); ?>
		<?php echo $form->numberField($model, 'status'); ?>
		<?php echo $form->error($model, 'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'note'); ?>
		<?php echo $form->textArea($model, 'note', array(
    'cols' => 25,
    'rows' => 3,
    'maxlength' => 255
)); ?>
		<?php echo $form->error($model, 'note'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Tk::g('添加') : Tk::g('Save')); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->