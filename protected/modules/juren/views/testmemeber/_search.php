<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	
	'action'=>Yii::app()->createUrl($this->route,array('t'=>Yii::app()->request->getQuery('t',0))),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'company'); ?>
		<?php echo $form->textField($model,'company',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active_time'); ?>
		<?php echo $form->textField($model,'active_time',array('size'=>10,'maxlength'=>10,'class'=>'date')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'add_time'); ?>
		<?php echo $form->textField($model,'add_time',array('size'=>10,'maxlength'=>10,'class'=>'date')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Tk::g('Search'),array('name'=>'search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->