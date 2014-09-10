<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs=array(
	Tk::g('Test Memebers') => array('admin'),
	Tk::g('Import'),
);
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'test-memeber-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'file'); ?>
		<?php echo $form->fileField($model,'file'); ?>
		<?php echo $form->error($model,'file'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Tk::g('Import')); ?>
	</div>

<?php $this->endWidget(); ?>

<hr />
<h2>
格式如下：
 	
<?php echo CHtml::link('点击下载表格',Yii::app()->getBaseUrl().'/upload/format.xls'); ?>
</h2>
<?php echo CHtml::image(Yii::app()->getBaseUrl().'/upload/format.jpg'); ?>

</div><!-- form -->
