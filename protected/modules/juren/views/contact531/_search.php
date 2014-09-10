<?php
/* @var $this Contact531Controller */
/* @var $model Contact531 */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>


	<div class="row">
		<?php echo $form->label($model,'clientele_name'); ?>
		<?php echo $form->textField($model,'clientele_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nicename'); ?>
		<?php echo $form->textField($model,'nicename',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mobile'); ?>
		<?php echo $form->textField($model,'mobile',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'web'); ?>
		<?php echo $form->textField($model,'web',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'business'); ?>
		<?php echo $form->textField($model,'business',array('size'=>60,'maxlength'=>255)); ?>
	</div>
<?php if(Tak::getAdmin()):?>
	<div class="row ">
		<?php echo $form->label($model,'add_time'); ?>
		<?php echo $form->textField($model,'add_time',array('size'=>10,'maxlength'=>10,'class'=>'date')); ?>
	</div>
<?php endif ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Tk::g('Search'),array('name'=>'search')); ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- search-form -->