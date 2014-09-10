<?php
$action = $model->isNewRecord ? 'Create' : 'Update';
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'address-groups-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
));
echo $form->errorSummary($model);
?>

<div class="row-fluid">
    <div class="row-form clearfix" style="border-top-width: 0px;">
		<?php echo $form->textFieldRow($model, 'name', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
	</div>
	<div class="row-form clearfix">
		<?php echo $form->radioButtonListRow($model, 'display', TakType::items('display') , array(
    'class' => '',
    'template' => '<label class="checkbox inline">{input}{label}</label>'
)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textAreaRow($model, 'note', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
	</div>
	<div class="row-form clearfix" >
		<?php echo $form->textFieldRow($model, 'listorder', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
	</div>
</div>

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'size' => 'large',
    'buttonType' => 'submit',
    'label' => Tk::g($action)
)); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'size' => 'large',
    'buttonType' => 'reset',
    'label' => Tk::g('Reset')
)); ?>    
</div>
<?php $this->endWidget(); ?>