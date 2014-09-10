<?php
/* @var $this ManageController */
/* @var $model Manage */

$this->breadcrumbs = array(
	'修改密码',
);
?>
<div class="form">
<?php 
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'verticalForm',
	'enableAjaxValidation'=>false,
)); ?>
<?php 
    echo $form->errorSummary($model); 
?>

	<div class="row">
		<?php echo $form->labelEx($model,'oldPasswd'); ?>
		<?php echo $form->passwordField($model,'oldPasswd'); ?>
		<?php echo $form->error($model,'oldPasswd'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'passwd'); ?>
		<?php echo $form->passwordField($model,'passwd'); ?>
		<?php echo $form->error($model,'passwd'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'passwdConfirm'); ?>
		<?php echo $form->passwordField($model,'passwdConfirm'); ?>
		<?php echo $form->error($model,'passwdConfirm'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('修改'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>