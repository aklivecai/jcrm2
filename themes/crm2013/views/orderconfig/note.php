<?php
$this->breadcrumbs = array(
    Tk::g('Order') => array(
        'order/admin'
    ) ,
    Tk::g('Order Note') ,
);
?>
<div class="page-header">
    <h1><?php echo Tk::g('Order Note') ?></h1>
</div>
<div class="block-fluid">
<?php $this->tab(); ?>
    <div class="row-form">
        
        <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'manage-form',
    'enableAjaxValidation' => false,
)); ?>
<div class="dr"><span></span></div>
<?php $this->widget('application.extensions.HtmlEdit', array(
    'model' => $model,
    'attribute' => 'item_value',
    'options' => array(
        'toolbar' => 'Edit',
        'height' => 350,
        'allowedContent' => false,
        'startupOutlineBlocks' => false,
    )
)); ?>
<div class="dr"><span></span></div>
    <div class="tar">

    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'size' => 'large',
    'buttonType' => 'submit',
    'label' => Tk::g('Save')
)); ?>
</div>

    </form>
</div>
</div>
<?php $this->endWidget(); ?>