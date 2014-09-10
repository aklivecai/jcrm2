<?php
/* @var $this OrderController */
/* @var $model Order */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php
  $action = $model->isNewRecord?'Create':'Update';

?>
<div class="row-fluid">
<div class="span12">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'order-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo Tk::g(array('Order',$action));?></h1>      
</div>
<div class="block-fluid">
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'total',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'pay_time',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'delivery_time',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'u_time',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'invoice_number',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textAreaRow($model,'note',array('size'=>60,'maxlength'=>255)); ?>
	</div>

</div>

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'submit', 'label'=>Tk::g($action))); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'reset', 'label'=>Tk::g('Reset'))); ?>
    
</div>

<?php $this->endWidget(); ?>
</div>
</div>