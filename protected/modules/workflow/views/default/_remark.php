<div class="row">
	<?php
	/*
	* 根据所处步骤,判断是否显示审批按钮
	 */
	if($step_no >0)
	{
		echo CHtml::label('审批意见', 'for', array('optionName'=>''));
		echo CHtml::dropDownList('type', 'select', array('同意','不同意'), array('optionName'=>''));
	}
	?>
	<?php echo CHtml::label('反馈信息', 'for', array('optionName'=>'')); ?>
	<?php echo CHtml::textArea('remark', '', array('optionName'=>'')); ?>
</div>