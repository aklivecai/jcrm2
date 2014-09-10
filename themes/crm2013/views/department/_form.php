<?php
$action = $model->isNewRecord ? 'Create' : 'Update';

if (!$this->isAjax) {
    $this->renderPartial('_tabs', array(
        'model' => $model,
        'action' => $action
    ));
}
?>
 <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'mod-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
)); ?>

<div class="block-fluid">
  <div class="row-form clearfix" style="border-top-width: 0px;">
    <?php echo $form->textFieldRow($model, 'name', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
  </div>
  <div class="row-form clearfix">
    <?php echo $form->textFieldRow($model, 'note', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
  </div>
  </div>  

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g($action)
)); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'reset',
    'label' => Tk::g('Reset')
)); ?>  
</div>
<?php $this->endWidget(); ?>
