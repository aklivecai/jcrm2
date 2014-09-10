<div class="row-fluid">
<div class="span12">
<?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'horizontalForm',
    'type' => 'horizontal',
));
?>
<?php echo $form->errorSummary($model); ?>

<div class="head clearfix">
  <i class="isw-documents"></i> <h1><?php echo Tk::g(array(
    'Manages',
    'Action'
)); ?></h1>
<?php
$items = array(
    array(
        'icon' => 'isw-edit',
        'url' => '#',
        'label' => Tk::g('Save') ,
        'linkOptions' => array(
            'class' => 'save',
            'submit' => array()
        ) ,
    )
);

if ($model->isNewRecord) {
} else {
    array_push($items, array(
        'icon' => 'isw-zoom',
        'url' => array(
            'view',
            'id' => $this->setSId($model->primaryKey)
        ) ,
        'label' => Tk::g('View') ,
    ) , array(
        'icon' => 'isw-plus',
        'url' => array(
            'create'
        ) ,
        'label' => Tk::g('Create New') ,
    ) , array(
        'icon' => 'isw-delete',
        'url' => array(
            'delete',
            'id' => $this->setSId($model->primaryKey)
        ) ,
        'label' => Tk::g('Delete') ,
        'linkOptions' => array(
            'class' => 'delete'
        ) ,
    ));
    
    $nps = $model->getNP(true);
    if (count($nps) > 0) {
        foreach ($nps as $key => $value) {
            $items[] = array(
                'label' => Tk::g($key) ,
                'icon' => 'isw-bookmark',
                'url' => array(
                    'update',
                    'id' => Tak::setSId($value) ,
                )
            );
        }
    }
}
$this->widget('application.components.MyMenu', array(
    'htmlOptions' => array(
        'class' => 'buttons'
    ) ,
    'items' => $items,
));
?>  
</div>
<div class="block-fluid">
    <div class="row-form clearfix" style="border-top-width: 0px;">
    <?php echo $form->textFieldRow($model, 'user_name', array(
    'class' => 'span9',
    'size' => 60,
    'maxlength' => 60
)); ?>
  </div>
  <div class="row-form clearfix">
    <?php echo $form->passwordFieldRow($model, 'user_pass', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
</div>
<div class="row-form clearfix">
    <?php echo $form->dropDownListRow($model, 'branch', $this->branchs); ?>
</div>
<div class="row-form clearfix">
    <?php echo $form->textFieldRow($model, 'user_nicename', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
</div><div class="row-form clearfix">
    <?php echo $form->textFieldRow($model, 'user_email', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
</div><div class="row-form clearfix">
  <?php echo $form->textAreaRow($model, 'note', array(
    'maxlength' => 255
)); ?>
    </div><div class="row-form clearfix">    
    <?php echo $form->dropDownListRow($model, 'isbranch', TakType::items('isbranch')); ?>

  </div>
  <div class="row-form">
  <div class="controls">
      <?php
echo CHtml::checkBox('Manage[user_status]', $model->user_status == 1, array(
    'class' => 'ibtn',
    'value' => 1
));
?>
  </div>
    </div>    
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
 
<?php $this->endWidget(); ?>
</div>
</div>
</div>