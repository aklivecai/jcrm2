<?php
echo $html; //自定义表单数据展示
/*
 * 判断是否可编辑
 * 生成相同页面内容页面,一个可编辑,一个不可编辑
*/
if ($disabled) {
?>
<div class="row-form clearfix">
	<?php echo $form->textFieldRow($model, 'flow_name', array(
        'class' => 'span2',
        'readonly' => 'true',
        'disabled' => 'disabled'
    )); ?>
</div>

<div class="row-form clearfix">
	<?php echo $form->textAreaRow($model, 'describe', array(
        'disabled' => 'disabled'
    )); ?>
</div>
<div class="row-form clearfix">
<?php echo CHtml::label('处理期限', 'for'); ?>
<?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'language' => 'zh_cn',
        'name' => 'FormModel[time]',
        'model' => $model,
        'value' => Date('Y-m-d') ,
        'options' => array(
            'showAnim' => 'fold',
            'showOn' => 'both',
            'maxDate' => '',
            'buttonImageOnly' => true,
            'dateFormat' => 'yy-mm-dd',
        ) ,
        'htmlOptions' => array(
            'disabled' => 'disabled',
            'style' => 'height:18px',
            'maxlength' => 8,
        ) ,
    ));
?>
</div>
<div class="row-form clearfix">
	<?php echo CHtml::label('附件', 'for', array()); ?>
	<?php echo CHtml::activeFileField($model, 'aimage'); ?>
</div>
<?php
} else {
?>
<div class="row-form clearfix">
	<?php echo $form->textFieldRow($model, 'flow_name', array(
        'class' => 'span2',
        'readonly' => 'true'
    )); ?>
</div>

<div class="row-form clearfix">
	<?php echo $form->textAreaRow($model, 'describe', array()); ?>
</div>

<div class="row-form clearfix">
<?php echo $form->textFieldRow($model, 'time', array(
        'class' => 'span2',
    )); ?>
  </div>

<div class="row-form clearfix">
<?php echo $form->textFieldRow($model, 'file', array(
        'class' => 'span2',
    )); ?>
  </div>
<?php
}
?>
<?php
// $this->widget('CMultiFileUpload', array(
//    'model'=>$model,
//    'attribute'=>'aimage',
//    'accept'=>'jpg|gif|pdf|apk',
//    'options'=>array(
//    ),
// ));


?>