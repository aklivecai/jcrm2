<?php
/* @var $this AddressBookController */
/* @var $model AddressBook */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php  $action = $model->isNewRecord?'Create':'Update';
 $items = Tak::getEditMenu($model->itemid,$model->isNewRecord);
?>
<div class="row-fluid">
<div class="span12">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'address-book-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>false,
	'focus'=>array($model,'name'),
)); ?>

<?php echo $form->errorSummary($model); ?>

<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo Tk::g(array('AddressBook',$action));?></h1>
		<?php 
		$this->widget('application.components.MyMenu',array(
		      'htmlOptions'=>array('class'=>'buttons'),
		      'items'=> $items ,
		));
		?>       
</div>
<div class="block-fluid">
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>64)); ?>
	</div>
	<div class="row-form clearfix">
<?php 
	$types = AddressGroups::model()->getList();
	if (count($types)>0) {
		echo $form->dropDownListRow($model,'groups_id',$types); 
	}else{
	$this->widget('bootstrap.widgets.TbButton', array(
    'type'=>'primary',
    'label'=>'还没有部门,点击录入',
    'block'=>true,
    'url' => Yii::app()->createUrl('AddressGroups/create',array('type'=>'product','returnUrl'=>Yii::app()->request->url)),
 )); 
 }	
?>
	</div>	
	
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'position',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->radioButtonListRow($model,'sex',TakType::items('sex'),array('class'=>'','template'=>'<label class="checkbox inline">{input}{label}</label>')); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'email',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'telephone',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'phone',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'address',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	<!--
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'longitude',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'latitude',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model,'location',array('size'=>60,'maxlength'=>255)); ?>
	</div>
	-->
	<div class="row-form clearfix">
		<?php echo $form->radioButtonListRow($model,'display',TakType::items('display'),array('class'=>'','template'=>'<label class="checkbox inline">{input}{label}</label>')); ?>
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