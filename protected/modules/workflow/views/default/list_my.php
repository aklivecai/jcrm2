<?php
/**
 *  当前用户,流程列表显示
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g('My Workflow');

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
    /***** 根据run_prc的数据排序,最后步骤数据数组索引为0 *****/
    array(
        'name' => 'step_name',
    ) ,
    array(
        'name' => 'cuser_name',
    ) ,
    array(
        'name' => 'describe',
        'header' => '描述'
    ) ,
    array(
        'header' => '状态',
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
