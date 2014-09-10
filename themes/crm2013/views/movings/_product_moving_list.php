<?php
/* @var $this MovingsController */
/* @var $data Movings */
if ($data->itemid == $data->product_id) {
    $time = $data->time_stocked;
    $use = Manage::getNameById($manageid);
    $enterprise = '';
    $cateName = '初始化库存';
} else {
    $time = $data->iMovings->time;
    $use = $data->iMovings->us_launch;
    $cateName = $cates[$data->iMovings->typeid];
    $enterprise = $data->iMovings->enterprise;
}
?>
<tr>
	<td><?php echo $data->iMovings->numbers; ?></td>
	<td><?php echo Warehouse::deisplayName($data->warehouse_id); ?></td>
	<td><?php echo CHtml::encode($enterprise); ?></td>
	<td><?php echo $cateName ?></td>
	<td class="txt-right"><?php echo CHtml::encode(Tak::getNums($data->numbers)); ?></td>
	<td class="txt-center"><?php echo Tak::timetodate($time, 3); ?></td>
	<td class="txt-center"><?php echo CHtml::encode($use); ?></td>
	<!-- <td class="txt-center"><?php echo Tak::timetodate($data->time_stocked, 6); ?></td> -->
	<td class="txt-center"><?php echo CHtml::encode($data->note); ?></td>
</tr>
<!--
$data->attributes
-->