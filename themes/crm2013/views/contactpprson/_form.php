<?php
/* @var $this ContactpPrsonController */
/* @var $model ContactpPrson */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php
$action = $model->isNewRecord ? 'Create' : 'Update';
$items = Tak::getEditMenu($model->itemid, $model->isNewRecord);

if (!$model->isNewRecord) {
    $items['Create']['url'] = array(
        'create',
        'ContactpPrson[clienteleid]' => $model->clienteleid
    );
}
?><div class="row-fluid">
    <div class="span12">

        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'contactp-prson-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
)); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="head clearfix">
            <i class="isw-documents"></i>
            <h1>
                <?php echo Tk::g(array(
    'ContactpPrson',
    $action
)); ?>
            </h1>
            <?php $this->widget('application.components.MyMenu', array(
    'htmlOptions' => array(
        'class' => 'buttons'
    ) ,
    'items' => $items,
)); ?>
        </div>
        <div class="block-fluid">
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'clienteleid', array(
    'class' => 'select-clientele',
    'size' => 20,
    'maxlength' => 20,
    'style' => 'width:100%'
)); ?>

            </div>

            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'nicename', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->radioButtonListRow($model, 'sex', TakType::items('sex') , array(
    'class' => '',
    'template' => '
                <label class="checkbox inline">{input}{label}</label>'
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'department', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'position', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'email', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'phone', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'mobile', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'fax', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'qq', array(
    'size' => 20,
    'maxlength' => 20
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model, 'address', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textAreaRow($model, 'note', array(
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
    </div>
</div>
