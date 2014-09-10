<?php
$action = $model->isNewRecord ? 'Add' : 'Update';
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'horizontalForm',
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
    'size' => 50,
    'maxlength' => 50
)); ?>
    </div>
    <div class="row-form clearfix"> 
        <?php
echo $form->dropDownListRow($model, 'user_name', $this->warehouseus, array(
    'multiple' => 'multiple',
    'class'=>'iselect',
    'style'=>'width:95%',
    'placeholder'=>'选择负责人',
)); ?>
    </div>
    <div class="row-form clearfix">
    <?php echo $form->textFieldRow($model, 'telephone', array(
    'size' => 50,
    'maxlength' => 50
)); ?>
    </div>
    <div class="row-form clearfix">
    <?php echo $form->textFieldRow($model, 'note', array(
    'size' => 50,
    'maxlength' => 50
)); ?>
    </div>
    <div class="footer tar">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => $model->isNewRecord ? Tk::g('Add') : Tk::g('Save')
)); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'reset',
    'label' => Tk::g('Reset')
)); ?>
    </div>
</div>
<?php $this->endWidget(); ?>