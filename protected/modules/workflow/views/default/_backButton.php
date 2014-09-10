<?php 
/*
* 未使用,流程回退按钮
*/
if(!empty($run_id) && $step_no >=0)
{
$url = Yii::app()->controller->createUrl("FlowBack", array("flow_id"=>$flow_id, "run_id"=>$run_id, "step_no"=>$step_no));
$this->widget('bootstrap.widgets.TbButton', array(
	"buttonType"=>"primary", 
	"type"=>"danger",
	"label"=>"流程退回",
	"url"=>Yii::app()->controller->createUrl("FlowBack", array("flow_id"=>$flow_id, "run_id"=>$run_id, "step_no"=>$step_no)),
	"htmlOptions" => array(""),
	)); 
}
?>
