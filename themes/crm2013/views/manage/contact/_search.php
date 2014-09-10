<?php
/* @var $this ContactController */
/* @var $model Contact */
/* @var $form CActiveForm */
?>

<div class="dr"><span></span></div>

<?php /** @var BootActiveForm $form */


$form = $this->beginWidget('CActiveForm', array(
    'id'=>'search-form',
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',    
)); ?>

<?php echo $form->dropDownList($model, 'type', TakType::sitems('contact-type')); ?>
<?php echo $form->dropDownList($model, 'stage', TakType::sitems('contact-stage')); ?>
		<?php echo $form->textField($model,'clienteleid',array('class'=>'select-clientele','size'=>10,'maxlength'=>10,'style'=>'width:100%')); ?>
<div class="more-search-info hide">	
<?php echo $form->textField($model,'prsonid',array('class'=>'select-prsonid','size'=>10,'maxlength'=>10,'style'=>'width:100%')); ?>
<?php echo $form->textField($model,'contact_time',array('size'=>10,'maxlength'=>10,'class'=>'type-date')); ?>

<?php echo $form->textField($model,'next_contact_time',array('size'=>10,'maxlength'=>10,'class'=>'type-date')); ?>

<?php echo $form->textField($model, 'next_subject'); ?>
<?php echo $form->textField($model, 'note'); ?>
</div>
<div class="footer">
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); ?>

 <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'button',
    'htmlOptions'=>array('class'=>'more-search'),
    'label'=>Tk::g('More'),
)); ?>
</div>
<?php $this->endWidget(); ?>

<div class="clear"></div>