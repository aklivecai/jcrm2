<?php
/*
* 条件设置页面
 */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'stepform',
    // 'type'=>'horizontal',
    'action'=>$this->createUrl('stepcreate', array('flow_id'=>$model->flow_id,'step_id'=>$model->step_id)),
    'htmlOptions'=>array('class'=>'well'),
));
?>
<?php echo $form->textFieldRow($model, 'step_name', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model, 'order', array('class'=>'span3')); ?>
<?php echo CHtml::label('步骤处理人', 'for', array()); ?>
<?php echo $form->dropDownList($model, 'step_user', Tool::userList()); ?>
<?php //echo CHtml::label('指定下一步骤', 'for', array()); ?>
<?php //echo $form->dropDownList($model, 'next_step', Tool::getAllstep($flow_id)); ?>
<?php echo CHtml::activeHiddenField($model, 'flow_id', array('optionName'=>'')); ?>
<?php echo CHtml::activeHiddenField($model, 'step_id', array('optionName'=>'')); ?>
<div class="well">
	<div class="text-info">根据流程需要,设置步骤条件</div>
	<div class="text-info">条件设置需要关联表单</div>
	<div class="text-info">条件为步骤转入条件</div>
	<hr>
字段:<?php 
	$form_id = 8;
	echo CHtml::dropDownList('condition', 'select', Tool::getFormField($form_id));
?>
条件:<?php echo CHtml::dropDownList('type', 'select', Tool::getConditionType()); ?>
值:<?php echo CHtml::textField('value', 'value'); ?>
</div>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'提交')); ?>
</div>
<?php $this->endWidget(); ?>
