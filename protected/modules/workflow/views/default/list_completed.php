<?php
/**
 *  已经完成的审批
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g('Completed');

$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->search();
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
        'header' => '结束步骤',
    ) ,
    array(
        'name' => 'cuser_name',
        'header' => '处理人',
    ) ,
    array(
        'name' => 'end_time',
        'header' => '结束时间/处理时长',
        'type' => 'raw',
        'value' => 'Tak::timetodate($data->end_time, 6)."<br />".Tak::timediff($data->start_time, $data->end_time)',
    ) ,
    array(
        'name' => 'describe',
        'header' => '描述'
    ) ,
    array(
        'header' => '操作',
        'value' => 'Yii::app()->getController()->getLink($data->primaryKey)',
    ) ,
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
