<?php
/* @var $this TestMemeberController */
/* @var $data TestMemeber */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('fromid')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->fromid), array('view', 'id'=>$data->fromid)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('manageid')); ?>:</b>
	<?php echo CHtml::encode($data->manageid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company')); ?>:</b>
	<?php echo CHtml::encode($data->company); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_time')); ?>:</b>
	<?php echo CHtml::encode($data->active_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_time')); ?>:</b>
	<?php echo CHtml::encode($data->add_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_us')); ?>:</b>
	<?php echo CHtml::encode($data->add_us); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('add_ip')); ?>:</b>
	<?php echo CHtml::encode($data->add_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_time')); ?>:</b>
	<?php echo CHtml::encode($data->modified_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_us')); ?>:</b>
	<?php echo CHtml::encode($data->modified_us); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_ip')); ?>:</b>
	<?php echo CHtml::encode($data->modified_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	*/ ?>

</div>