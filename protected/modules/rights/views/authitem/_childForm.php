<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>
		<?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>	
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Rights::t('core', 'Add'))); ?>
<?php $this->endWidget(); ?>