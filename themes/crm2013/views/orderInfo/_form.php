<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php  $action = $model->isNewRecord?'Create':'Update';
 $items = Tak::getEditMenu($model->itemid,$model->isNewRecord);
?>
<div class="row-fluid">
<div class="span12">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'order-info-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->hiddenField($model,'itemid'); ?>

<?php echo $form->errorSummary($model); ?>

<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo Tk::g(array('OrderInfo',$action));?></h1>
	<ul class="buttons">
	    <li>
	        <a href="#" class="isw-settings"></a>
<?php			$this->widget('application.components.MyMenu',array(
	          'htmlOptions'=>array('class'=>'dd-list'),
	          'items'=> $items ,
	    	));
		?>
	    </li>
	</ul>       
</div>
<div class="block-fluid">
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'date_time',array('size'=>10,'maxlength'=>10,'type'=>'date')); ?>
	</div>
	<div class="row-form clearfix" >
	<?php echo $form->dropDownListRow($model,'detype',OrderType::items('detype')); ?>
	</div>
	<div class="row-form clearfix" >
	<?php echo $form->dropDownListRow($model,'pay_type',OrderType::items('pay_type')); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'earnest'); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'few_day'); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'delivery_before'); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'remaining_day'); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->dropDownListRow($model,'packing',OrderType::items('packing')); ?>
	</div>
	<div class="row-form clearfix" >
	<?php echo $form->dropDownListRow($model,'taxes',OrderType::items('taxes')); ?>
	</div>
	<div class="row-form clearfix" >
	<?php echo $form->dropDownListRow($model,'convey',OrderType::items('convey')); ?>
	</div>
	<div class="row-form clearfix" >
	<?php echo $form->dropDownListRow($model,'area',OrderType::items('area')); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'address',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'people',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'tel',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'phone',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'purchasconsign',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'contactphone',array('size'=>50,'maxlength'=>50)); ?>
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