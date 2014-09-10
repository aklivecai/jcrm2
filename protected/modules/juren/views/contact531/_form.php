
<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'test-memeber-form',
    'enableAjaxValidation' => false,
)); ?>

<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'clientele_name'); ?>
		<?php echo $form->textField($model, 'clientele_name', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
		<?php echo $form->error($model, 'clientele_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'nicename'); ?>
		<?php echo $form->textField($model, 'nicename', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
		<?php echo $form->error($model, 'nicename'); ?>
	</div>
		<div class="row">
		<?php echo $form->labelEx($model, 'mobile'); ?>
		<?php echo $form->textField($model, 'mobile', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
		<?php echo $form->error($model, 'mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'phone'); ?>
		<?php echo $form->textField($model, 'phone', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
		<?php echo $form->error($model, 'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'address'); ?>
		<?php echo $form->textField($model, 'address', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
		<?php echo $form->error($model, 'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'web'); ?>
		<?php echo $form->textField($model, 'web', array(
    'size' => 60,
    'maxlength' => 50
)); ?>
		<?php echo $form->error($model, 'web'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'business'); ?>
		<?php echo $form->textField($model, 'business', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
		<?php echo $form->error($model, 'business'); ?>
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