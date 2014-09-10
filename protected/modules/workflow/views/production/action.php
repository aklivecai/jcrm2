<?php
$action = $model->isNewRecord ? 'Create' : 'Update';
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'mod-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'tak-form'
    ) ,
));
?>
<table class="itable bordered">
    <colgroup >
    <col width="25%"/>
    <col width="auto"/>
    </colgroup>
    <tbody>
        <tr>
            <th>
                <?php echo $form->labelEx($model, 'flow_name'); ?>
            </th>
            <td>
                <?php echo $form->textField($model, 'flow_name', array(
    'size' => 25,
    'maxlength' => 25
)); ?>
                <?php echo $form->error($model, 'flow_name'); ?>
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $form->labelEx($model, 'note'); ?>
            </th>
            <td>
                <?php echo $form->textArea($model, 'note', array(
    'size' => 25,
    'maxlength' => 25
)); ?>
                <?php echo $form->error($model, 'note'); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td>
                <?php echo Tak::submitButton(Tk::g($action)); ?>
            </td>
        </tr>
    </tfoot>
</table>
<?php echo $form->errorSummary($model); ?>
<?php $this->endWidget(); ?>
