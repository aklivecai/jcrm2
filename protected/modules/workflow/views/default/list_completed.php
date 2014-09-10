<?php
/**
 *  已经完成的审批
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g('Completed');
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $gridDataProvider,
    'enablePagination' => true,
    'enableSorting' => false,
    'template' => "{items}{pager}",
    'emptyText' => '没有数据.',
    'columns' => array(
        array(
            'name' => 'run_id',
            'header' => '#'
        ) ,
        array(
            'name' => 'flow_name',
            'header' => '流程名称'
        ) ,
        array(
            'name' => 'begin_user',
            'header' => '创建人',
            'value' => 'Tool::getUname($data->begin_user)'
        ) ,
        array(
            'name' => 'prc_data.time',
            'header' => '处理期限'
        ) ,
        // array('name'=>'run_prc', 'header'=>'处理期限'),
        array(
            'name' => 'prc_data.describe',
            'header' => '描述'
        ) ,
        array(
            'name' => '',
            'header' => '',
            'value' => 'Yii::app()->controller->writeButtons($data)'
        ) ,
        // array('name'=>'step_no', 'header'=>'步骤号'),
        
        
    ) ,
));
?>
