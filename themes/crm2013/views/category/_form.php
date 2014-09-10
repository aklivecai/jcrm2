<?php
$action = $model->isNewRecord ? 'Create' : 'Update';
?>
<div class="row-fluid">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'mod-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
));
echo $form->errorSummary($model);
?>
        <?php if (!$this->isAjax): ?>
        <div class="head clearfix">
            <i class="isw-documents"></i>
            <h1><?php echo Tk::g(array(
        $action,
        'Creategory'
    )); ?></h1>
        </div>
        <?php
endif
?>
        <div class="block-fluid">
            <div class="row-form clearfix" style="border-top-width: 0px;">
                <div class="control-group ">
                    <label class="control-label" for="Category_parentid">上级分类</label>
                    <div class="controls">
                        <span class="span10">
<?php
$this->renderPartial('select', array(
    'id' => sprintf("%s[parentid]", $model->mName) ,
    'value' => $model->parentid,
));
?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row-form clearfix">
                <?php
if ($model->isNewRecord && false) {
    echo $form->textFieldRow($model, 'catename', array(
        'size' => 60,
        'maxlength' => 60,
    ));
} else {
    echo $form->textFieldRow($model, 'catename', array(
        'size' => 60,
        'maxlength' => 100,
        'autofocus' => "true",
    ));
}
?>
            </div>
            <div class="row-form clearfix">
<?php echo $form->textFieldRow($model, 'listorder', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
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
    </div>
</div>