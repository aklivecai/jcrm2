<?php
/* @var $this MovingsController */
/* @var $data Movings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('itemid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->itemid), array('view', 'id'=>$data->itemid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fromid')); ?>:</b>
	<?php echo CHtml::encode($data->fromid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numbers')); ?>:</b>
	<?php echo CHtml::encode($data->numbers); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('typeid')); ?>:</b>
	<?php echo CHtml::encode($data->typeid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enterprise')); ?>:</b>
	<?php echo CHtml::encode($data->enterprise); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('us_launch')); ?>:</b>
	<?php echo CHtml::encode($data->us_launch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_stocked')); ?>:</b>
	<?php echo CHtml::encode($data->time_stocked); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_time')); ?>:</b>
	<?php echo CHtml::encode($data->add_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_ip')); ?>:</b>
	<?php echo CHtml::encode($data->add_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo CHtml::encode($data->modified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_ip')); ?>:</b>
	<?php echo CHtml::encode($data->modified_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>