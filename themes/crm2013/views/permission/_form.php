<?php
/* @var $this ClienteleController */
/* @var $model Clientele */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
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
    <?php echo $form->textFieldRow($model, 'description', array(
    'size' => 60,
    'maxlength' => 100
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
<?php
if (!$this->tabs) {
    Tak::regScript('bodyend', "   intro =new  introJs();
           intro.setOptions({
                    steps: [
                      {
                        element: document.querySelector('#Permission_description'),
                        intro: '管理用户,得先新建部门.',
                        position: 'button'
                      }
                    ]
              });
            intro.start();          
    ");
}
?>    
