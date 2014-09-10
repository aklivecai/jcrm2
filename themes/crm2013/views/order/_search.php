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

$info = new OrderInfo();
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
            <th></th>
            <td colspan="4">
                <?php
                echo $form->dropDownList($model, 'status', Order::getSearchStatus());
                echo ' ';
                echo $form->dropDownList($model, 'manageid', Order::getUsersSelect());
                ?>
            </td>
        </tr>
        <tr>
            <th><?php echo $form->label($model, 'company') ?></th>
            <td> <?php echo $form->textField($model, 'company'); ?></td>
            <th>
            <?php echo CHtml::activeLabelEx($model, 'serialid') ?></th>
            <td><?php echo CHtml::activeTextField($model, 'serialid'); ?></td>
            <td></td>
        </tr>
    </tbody>
    <tbody  class="more-content hide">
            <tr>
                <th><?php echo CHtml::activeLabelEx($model, 'itemid') ?></th>
                <td colspan="4"> <?php echo CHtml::activeTextField($model, 'itemid', array()); ?></td>
            </tr>
        <tr>
            <th><?php echo $form->label($model, 'total') ?></th>
            <td colspan="4">
                <?php echo CHtml::dropDownList('comparison', Tak::getQuery('comparison') , TakType::items('comparison')) ?>
                <?php echo $form->textField($model, 'total'); ?>
            </td>
        </tr>
        <tr>
            <th><?php echo $form::label($model, 'add_time') ?></th>
            <td>
                <?php echo CHtml::textField('time[add_time][]', $_GET['time']['add_time']['0'], array(
                'class' => 'type-date',
                'id' => 'time-start'
                )); ?>
                至
                <?php echo CHtml::textField('time[add_time][]', $_GET['time']['add_time']['1'], array(
                'class' => 'type-date',
                'id' => 'time-end'
                )); ?>
            </td>
            <th><?php echo CHtml::label($info->getAttributeLabel('date_time') , 'date_time') ?></th>
            <td>
                <?php echo CHtml::textField('time[date_time][]', $_GET['time']['date_time'][0], array(
                'class' => 'type-date',
                'id' => 'date_start'
                )); ?>
                至
                <?php echo CHtml::textField('time[date_time][]', $_GET['time']['date_time'][1], array(
                'class' => 'type-date',
                'id' => 'date_end'
                )); ?>
            </td>
        </tr>
        <tr>
            <th><?php echo CHtml::label('产品', 'info-product') ?></th>
            <td colspan="4">
                <?php echo CHtml::textField('info-product', $_GET['info-product']) ?>
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