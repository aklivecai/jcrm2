<div class="tak-smail">
<?php if( $model->scenario==='update' ): ?>
<div class="head clearfix">
 <div class="isw-cloud"></div>
 <h1><?php echo Rights::getAuthItemTypeName($model->type); ?></h1>
</div>
<?php endif; ?>

<div class="block">
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>
	<div class="row-form clearfix">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('maxlength'=>255, 'class'=>'text-field')); ?>
		<?php echo $form->error($model, 'name'); ?>
		<p class="hint"><?php echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></p>
	</div>

	<div class="row-form clearfix">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'text-field')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<p class="hint"><?php echo Rights::t('core', 'A descriptive name for this item.'); ?></p>
	</div>

	<?php if( Rights::module()->enableBizRule===true ): ?>
		<div class="row-form clearfix">
			<?php echo $form->labelEx($model, 'bizRule'); ?>
			<?php echo $form->textField($model, 'bizRule', array('maxlength'=>255, 'class'=>'text-field')); ?>
			<?php echo $form->error($model, 'bizRule'); ?>
			<p class="hint"><?php echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></p>
		</div>

	<?php endif; ?>

	<?php if( Rights::module()->enableBizRule===true && Rights::module()->enableBizRuleData ): ?>

		<div class="row-form clearfix">
			<?php echo $form->labelEx($model, 'data'); ?>
			<?php echo $form->textField($model, 'data', array('maxlength'=>255, 'class'=>'text-field')); ?>
			<?php echo $form->error($model, 'data'); ?>
			<p class="hint"><?php echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></p>
		</div>

	<?php endif; ?>

	<div class="row-form clearfix">
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Rights::t('core', 'Save'))); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('url'=>Yii::app()->user->rightsReturnUrl,'type'=>'info', 'label'=>Rights::t('core', 'Cancel'))); ?>

	</div>

<?php $this->endWidget(); ?>
</div>
</div>