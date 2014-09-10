<div class="form">
	<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'test-memeber-form',
	'enableAjaxValidation' => false,
	)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php
		$key = 'fromid';
		echo $form->label($model, $key);
		$this->renderPartial('/chip/mangeid', array(
		'id' => $key,
		'name' => $_GET['name'],
		'value' => $model->{$key},
		)); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton(Tk::g('Sublimt')); ?>
	</div>
	<?php echo $form->hiddenField($model, 'fromid'); ?>
	<?php $this->endWidget(); ?>
	</div><!-- form -->