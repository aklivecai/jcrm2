<?php
/**
 *  当前用户,需要处理的流程
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g('My Neet');

$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->search();
$columns = array(
    array(
        'name' => 'run_id',
        'header' => ''
    ) ,
    array(
        'name' => 'flow_name',
        'header' => '流程'
    ) ,
    'title',
    array(
        'name' => 'user',
        'value' => '$data->username',
    ) ,
    array(
        'name' => 'step_name',
        'header' => '当前步骤'
    ) ,
    array(
        'header' => '转入时间',
        'value' => 'Tak::timetodate($data->modified_time,6)',
        'headerHtmlOptions' => array(
            'style' => 'width: 125px'
        ) ,
    ) ,
    array(
        'header' => '操作',
        'value' => 'Yii::app()->getController()->getLink($data->primaryKey)',
    ) ,
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
