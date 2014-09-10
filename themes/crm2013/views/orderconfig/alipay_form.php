<?php
$action = $model->isNewRecord ? 'Create' : 'Update';
$this->breadcrumbs = array(
Tk::g('Alipay') => array(
'alipay'
) ,
Tk::g(array(
$action,
'Alipay'
)) ,
);
?>
<div class="page-header">
    <h1><?php echo Tk::g('Alipay') ?> <small><?php echo Tk::g($action) ?></small></h1>
</div>
<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'mod-form',
    'type' => 'horizontal',
    ));
    echo $form->errorSummary($model);
    ?>
    <div class="block-fluid">
        <?php $this->tab() ?>

        <div class="row-form clearfix">
            <?php
            echo $form->textFieldRow($model, 'title', array(
            'size' => 60,
            'maxlength' => 60,
            ));
            ?>
        </div>
        <div class="row-form clearfix">
            <?php echo $form->textFieldRow($model, 'listorder', array(
            'size' => 60,
            'maxlength' => 255
            )); ?>
        </div>
        <div class="row-form clearfix">
            <?php $this->widget('application.extensions.HtmlEdit', array(
            'name' => 'tak_content',
            'value' => $model->content,
            'options' => array(
            'toolbar' => 'Edit',
            'height' => 200,
            'allowedContent' => false,
            'startupOutlineBlocks' => false,
            )
            )); ?>
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
    </div>