<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

// 客户联系记录 任务
// 库存管理 任务
// 查看公司所有客户 任务
// 通讯录部门管理 任务
// 通讯录列表
$this->breadcrumbs=array(
	Tk::g($this->modelName)=>array('admin'),
	Tk::g('Admin'),
);
?>
<?php $this->renderPartial('_tabs', array('model'=>$model)); ?>
<div class="tab-content">
	<h2>添加</h2>
</div>
