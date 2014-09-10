<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */
/* @var $form CActiveForm */
?>

<div class="wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'itemid'); ?>
		<?php echo $form->textField($model,'itemid',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_time'); ?>
		<?php echo $form->textField($model,'date_time',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'detype'); ?>
		<?php echo $form->textField($model,'detype'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pay_type'); ?>
		<?php echo $form->textField($model,'pay_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'earnest'); ?>
		<?php echo $form->textField($model,'earnest'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'few_day'); ?>
		<?php echo $form->textField($model,'few_day'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'delivery_before'); ?>
		<?php echo $form->textField($model,'delivery_before'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'remaining_day'); ?>
		<?php echo $form->textField($model,'remaining_day'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'packing'); ?>
		<?php echo $form->textField($model,'packing'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'taxes'); ?>
		<?php echo $form->textField($model,'taxes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'convey'); ?>
		<?php echo $form->textField($model,'convey'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'area'); ?>
		<?php echo $form->textField($model,'area',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'people'); ?>
		<?php echo $form->textField($model,'people',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tel'); ?>
		<?php echo $form->textField($model,'tel',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'purchasconsign'); ?>
		<?php echo $form->textField($model,'purchasconsign',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contactphone'); ?>
		<?php echo $form->textField($model,'contactphone',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'add_ip'); ?>
		<?php echo $form->textField($model,'add_ip',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- search-form -->