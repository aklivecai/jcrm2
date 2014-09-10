<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php  
	$action = $model->isNewRecord?'Create':'Update';
 	$items = Tak::getEditMenu($model->itemid,$model->isNewRecord);
?>
<div class="row-fluid">
<div class="span12">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'product-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>true,
)); ?>
<?php echo $form->errorSummary($model); ?>
<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo Tk::g(array('Product',$action));?></h1>
<?php 
$this->widget('application.components.MyMenu',array(
      'htmlOptions'=>array('class'=>'buttons'),
      'items'=> $items ,
));
?>      
</div>
<div class="block-fluid">
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>
 <div class="row-form clearfix">
  <div class="control-group ">
    <label class="control-label">
    		<?php echo $model->getAttributeLabel('typeid')?>
    </label>
    <div class="controls">
                <span class="span10">

<?php
$this->renderPartial('/category/select', array(
        'id' => sprintf("%s[typeid]", $model->mName),
        'value' => $model->typeid,
        'add' => true
)); 
?>                
        </span>
    </div>
</div>
</div>	
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'price',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'material',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'spec',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'color',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'unit',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textAreaRow($model,'note',array()); ?>
	</div>

</div>
<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g($action))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>Tk::g('Reset'))); ?>    
</div>
<?php $this->endWidget(); ?>
</div>
</div>