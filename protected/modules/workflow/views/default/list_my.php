<?php
/**
 *  当前用户,流程列表显示
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g('My Workflow');
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
        /***** 根据run_prc的数据排序,最后步骤数据数组索引为0 *****/
        array(
            'name' => 'run_prc.0.step_name',
            'header' => '当前步骤'
        ) ,
        array(
            'name' => 'run_prc.0.step_user',
            'header' => '当前步骤处理人',
            'value' => 'Tool::getUname($data->run_prc[0]["step_user"])'
        ) ,
        array(
            'name' => 'prc_data.describe',
            'header' => '描述'
        ) ,
        array(
            'header' => '状态',
            'value' => array(
                $this,
                'flowState'
            )
        ) ,
        array(
            'htmlOptions' => array(
                'nowrap' => 'nowrap'
            ) ,
            'template' => '{detail}',
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'buttons' => array(
                'detail' => array(
                    'label' => '<span>流程查看</span>',
                    'title' => '流程查看',
                    'options' => array(
                        'title' => '',
                        'target' => '_balnk'
                    ) ,
                    'url' => 'Yii::app()->controller->createUrl("FlowDetail", array("run_id"=> $data["run_id"]))', // 查看流程
                    'imageUrl' => false,
                ) ,
            ) ,
        ) ,
    ) ,
));
?>
