<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */
/* @var $form CActiveForm */
$model->active_time = $model->active_time == 0 ? '' : $model->active_time;
?>

<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'test-memeber-form',
    'enableAjaxValidation' => false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php if (Tak::getAdmin() && !$model->isNewRecord): ?>
	<div class="row">
		<?php echo $form->labelEx($model, 'manageid'); ?>
		<?php echo $form->dropDownList($model, 'manageid', $manages); ?>
		<?php echo $form->error($model, 'manageid'); ?>
	</div>
	<?php
endif ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'company'); ?>
		<?php echo $form->textField($model, 'company', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
		<?php echo $form->error($model, 'company'); ?>
	</div>
	<?php if ($model->start_time > 0): ?>
		<div class="row">
		<strong>激活时间</strong>：<?php echo Tak::timetodate($model->start_time, 6) ?>
		</div>	
	<?php
else: ?>
		<div class="row">
		<strong>未激活</strong>
		</div>
	<div class="row">
		<?php echo $form->label($model, 'user_name'); ?>
		<?php echo $form->textField($model, 'user_name', array(
        'size' => 25,
        'maxlength' => 60
    )); ?>
	</div>			
	<?php
endif ?>
	<div class="row">
		<?php echo $form->label($model, 'active_time'); ?>
		<?php echo $form->textField($model, 'active_time', array(
    'size' => 15,
    'maxlength' => 10,
    'class' => 'date'
)); ?>
	</div>	

	<div class="row">
		<?php echo $form->labelEx($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
		<?php echo $form->error($model, 'email'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'logo'); ?>
		<?php echo $form->textField($model, 'logo', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
		<?php echo $form->error($model, 'email'); ?>
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