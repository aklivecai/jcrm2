<?php 
/*
*步骤增加表单,未使用
 */
$this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'增加步骤',
    'type'=>'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'small', // null, 'large', 'small' or 'mini'
    // 'block'=>true,
    // 'toggle'=>true,
    'htmlOptions'=>array('id'=>'add_step'),
));
?>

<div id="step_form">
<fieldset>
    <?php echo $form->textFieldRow($model, "[0]step_name"); ?>
    <?php echo $form->dropDownListRow($model, '[0]step_user', Tool::userList()); ?>
</fieldset>
</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'提交')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>
<script>
$('#add_step').click(function(){
    var i = $(this).data('degree') || 1;
    var option = "<?php echo Tool::userListJs();?>";
	var tmp = "<fieldset><label class='required'>步骤名称<span class='required'>*</span></label><input name='FlowStep["+i+"][step_name]' type='text' /><label for='FlowStep_i_step_user' class='required'>步骤处理人<span class='required'>*</span></label><select name='FlowStep["+i+"][step_user]' id='FlowStep_i_step_user'>"+option+"</select></fieldset>";
	$('#step_form').append(tmp);
    $(this).data('degree', ++i);
});
</script> 