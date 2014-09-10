<?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
'id' => 'search-form',
'htmlOptions' => array(
'class' => 'form-search'
) ,
'action' => Yii::app()->createUrl($this->route) ,
'method' => 'get',
));

if (!isset($_GET['time'])) {
    $_GET['time'] = array();
}
?>
<table class="table table-search">
    <colgroup align="center">
    <col width="90px"/>
    <col width="125px"/>
    <col width="90px"/>
    <col width="125px"/>
    </colgroup>
    <tbody>
        <tr>
            <th><?php echo $form->label($model, 'name') ?></th>
            <td> <?php echo $form->textField($model, 'name'); ?></td>
            <th>
            <?php echo $form->label($model, 'serialid') ?></th>
            <td><?php echo $form->textField($model, 'serialid'); ?>
            </td>
            <td></td>
        </tr>

        <tr>
            <th>
                <?php echo $form->label($model, 'company') ?>
            </th>
            <td colspan="4">
            <?php echo $form->textField($model, 'company'); ?>
            </td>
            </tr>
    </tbody>
    <tbody  class="more-content hide">
    <tr>
            <th><?php echo $form->label($model, 'sum') ?></th>
            <td colspan="4">
                <?php echo CHtml::dropDownList('comparison', Tak::getQuery('comparison') , TakType::items('comparison')) ?>
                <?php echo $form->textField($model, 'sum'); ?>
            </td>
        </tr>
        <tr>
            <th><?php echo $form::label($model, 'order_time') ?></th>
            <td>
                <?php echo CHtml::textField('time[order_time][]', $_GET['time']['order_time']['0'], array(
                'class' => 'type-date',
                'id' => 'time-start'
                )); ?>
                至
                <?php echo CHtml::textField('time[order_time][]', $_GET['time']['order_time']['1'], array(
                'class' => 'type-date',
                'id' => 'time-end'
                )); ?>
            </td>
            <th><?php echo CHtml::label($model->getAttributeLabel('complete_time') , 'complete_time') ?></th>
            <td colspan="2">
                <?php echo CHtml::textField('time[complete_time][]', $_GET['time']['complete_time'][0], array(
                'class' => 'type-date',
                'id' => 'date_start'
                )); ?>
                至
                <?php echo CHtml::textField('time[complete_time][]', $_GET['time']['complete_time'][1], array(
                'class' => 'type-date',
                'id' => 'date_end'
                )); ?>
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $form->label($model, 'product') ?>
            </th>
            <td>
            <?php echo $form->textField($model, 'product'); ?>
            </td>
            <th>
                <?php echo $form->label($model, 'amount') ?>
            </th>
            <td colspan="2">
            <?php echo $form->textField($model, 'amount'); ?>
            </td>

            </tr>
            <tr>
                <th><?php echo $form->label($model, 'note') ?></th>
                <td colspan="4"> <?php echo $form->textArea($model, 'note', array()); ?></td>
            </tr>
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <td colspan="4">
                <?php echo JHtml::htmlButton(Tk::g('Search') , array(
                'class' => 'btn',
                'type' => 'submit'
                )) ?>
                <?php echo JHtml::htmlButton(Tk::g('More') , array(
                'class' => 'btn btn-more'
                )) ?>
            </td>
        </tr>
        </tfoot>
    </table>
    <?php
    $this->endWidget();
    ?>