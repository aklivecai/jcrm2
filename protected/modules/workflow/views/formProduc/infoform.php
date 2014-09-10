<?php
/*
* 表单设置页面，未使用
 */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'flowinfoform',
    'htmlOptions'=>array('class'=>'well'),
    'action'=>$this->createUrl('Create'),
    'enableAjaxValidation'=>false,
    // 'type'=>'inline',
));
?>
<div>
<?php echo $form->textFieldRow($formModel, 'form_name', array()); ?>
</div>
<div class="">
	<h4>表单字段</h4>
	<p class="bg-success"></p>
	<div>
</div>
<?php
if(Yii::app()->user->hasFlash('error'))
{
    $this->widget('bootstrap.widgets.TbAlert', array(
            'block'=>true, // display a larger alert block?
            'fade'=>true, // use transitions?
            'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
            'alerts'=>array( // configurations per alert type
                'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
            ),
        ));
}
?>

<table id="are_field" class="items table table-striped table-bordered table-condensed">
<thead>
<tr>
 <th>
 <?php echo $fieldModel->getAttributeLabel('field_name');?>
 </th>
 <th>
 <?php echo $fieldModel->getAttributeLabel('field_type');?>
 </th>
 <th>
 <?php echo $fieldModel->getAttributeLabel('field_value');?>
 </th>
 <th>
 <?php echo $fieldModel->getAttributeLabel('field_default');?>
 </th>
 <th>
 操作
 </th>
</tr>
</thead>
<tbody>
<tr id="base_field">
<td><?php echo $form->textField($fieldModel, '[0]field_name'); ?></td>
<td><?php echo $form->dropDownList($fieldModel, '[0]field_type', $fieldModel->field_type,array('class'=>'inline')); ?></td>
<td><?php echo $form->textField($fieldModel, '[0]field_value',array('class'=>'hide')); ?></td>
<td><?php echo $form->textField($fieldModel, '[0]field_default'); ?></td>
<td><a class='delete btn btn-small' href="#">删除</a></td>
</tr>
</tbody>
<tfoot>
	<tr>
		<td colspan="5">
        <a id="add_field" class="btn btn-info btn-small">添加字段</a>
        <strong>提示:下拉列表的值域请使用||(两个竖线)分割</strong>
        </td>
	</tr>
</tfoot>
</table>
<div>
<?php
 $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'提交')); ?>
 </div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.delete').live('click',function(){
        $(this).parent().parent().remove();
    });
});
var field_html = $('#base_field').html();
$('#add_field').click(function(){
    var i = $(this).data('degree') || 1;
    var _t = field_html.replace(new RegExp( "0", "gi" ), i);
    $('#are_field').append('<tr>'+_t+'</tr>');
    $(this).data('degree', ++i);
});
</script>