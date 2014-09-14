<?php
/**
 *  当前用户,已经处理过的
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g('Handle');

$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->searchByUid();
$columns = array(
    array(
        'name' => 'run_id',
        'header' => ''
    ) ,
    array(
        'name' => 'title',
    ) ,
    array(
        'name' => 'start_time',
        'value' => 'Tak::timetodate($data->start_time,6)',
        'headerHtmlOptions' => array(
            'style' => 'width: 125px'
        ) ,
    ) ,
    array(
        'name' => 'flow_name',
        'header' => '流程'
    ) ,
    array(
        'name' => 'step_name',
    ) ,
    array(
        'name' => 'cuser_name',
        'header' => '处理人',
    ) ,
    array(
        'header' => '步骤转入时间',
        'value' => 'Tak::timetodate($data->modified_time,6)',
        'headerHtmlOptions' => array(
            'style' => 'width: 125px'
        ) ,
    ) ,
    array(
        'name' => 'describe',
        'header' => '描述'
    ) ,
    array(
        'name' => 'status',
    ) ,
    array(
        'header' => '操作',
        'value' => 'Yii::app()->getController()->getLink($data->primaryKey)',
    ) ,
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
