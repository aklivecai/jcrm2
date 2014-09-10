<?php
/* @var $this OrderProductController */
/* @var $model OrderProduct */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php  $action = $model->isNewRecord?'Create':'Update';
 $items = Tak::getEditMenu($model->itemid,$model->isNewRecord);

?>
<div class="row-fluid">
<div class="span12">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'order-product-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->hiddenField($model,'fromid'); ?>

<?php echo $form->errorSummary($model); ?>

<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo Tk::g(array('OrderProduct',$action));?></h1>
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
		<?php echo $form->textFieldRow($model,'order_id',array('size'=>25,'maxlength'=>25)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'model',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'standard',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'color',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'unit',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'amount',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'price',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'sum',array('size'=>10,'maxlength'=>10)); ?>
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