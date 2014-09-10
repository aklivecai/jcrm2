<!-- 未使用,之前默认首页 -->
<div class="container">
<?php
$this->widget('bootstrap.widgets.TbHeroUnit', array(
    'heading'=>'根据虚拟项目ID,生成流程',
));
?>
<div class="row ">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'生成流程',
    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'large', // null, 'large', 'small' or 'mini'
    'htmlOptions'=>array('href'=>$this->createUrl('FlowInfo',array('projid'=>$number))),
));
?>
<div class="span4 offset9">
<?php 
$this->widget('bootstrap.widgets.TbButtonGroup', array(
	'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	'buttons'=>array(
	array('label'=>'流程选择', 'url'=>'#'),
		array('items'=>$buttonArr),
	),
));?>

<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'label'=>'我的流程',
		'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
		'size'=>'large', // null, 'large', 'small' or 'mini'
		'htmlOptions'=>array('href'=>$this->createUrl('flowlist')),
	));
?>
	</div>
</div>

</div>