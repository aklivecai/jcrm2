<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs=array(
	Tk::g('Test Memebers') => array('admin'),
	Tk::g('Volume'),
);
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'test-memeber-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Tk::g('Volume')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
