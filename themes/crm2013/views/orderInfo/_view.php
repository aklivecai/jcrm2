<?php
/* @var $this OrderInfoController */
/* @var $data OrderInfo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('itemid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->itemid), array('view', 'id'=>$data->itemid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_time')); ?>:</b>
	<?php echo CHtml::encode($data->date_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('detype')); ?>:</b>
	<?php echo CHtml::encode($data->detype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pay_type')); ?>:</b>
	<?php echo CHtml::encode($data->pay_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('earnest')); ?>:</b>
	<?php echo CHtml::encode($data->earnest); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('few_day')); ?>:</b>
	<?php echo CHtml::encode($data->few_day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_before')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_before); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('remaining_day')); ?>:</b>
	<?php echo CHtml::encode($data->remaining_day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('packing')); ?>:</b>
	<?php echo CHtml::encode($data->packing); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('taxes')); ?>:</b>
	<?php echo CHtml::encode($data->taxes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('convey')); ?>:</b>
	<?php echo CHtml::encode($data->convey); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('area')); ?>:</b>
	<?php echo CHtml::encode($data->area); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('people')); ?>:</b>
	<?php echo CHtml::encode($data->people); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tel')); ?>:</b>
	<?php echo CHtml::encode($data->tel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('purchasconsign')); ?>:</b>
	<?php echo CHtml::encode($data->purchasconsign); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contactphone')); ?>:</b>
	<?php echo CHtml::encode($data->contactphone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_ip')); ?>:</b>
	<?php echo CHtml::encode($data->add_ip); ?>
	<br />

	*/ ?>

</div>