<?php
/*
*流程的详情页面
 */

/*
* 流程的详细信息
 */
$state_text = array('未结束','已结束');
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$model,//array('id'=>1, 'firstName'=>'Mark', 'lastName'=>'Otto', 'language'=>'CSS'),
    'attributes'=>array(
        array('name'=>'run_id', 'label'=>'流程ID'),
        array('name'=>'flow_name', 'label'=>'流程名称'),
        array('name'=>'begin_user', 'label'=>'流程开始人','value'=>Tool::getUname($model->begin_user)),
        array('name'=>'start_time', 'label'=>'流程开始时间', 'value'=>Tool::dateFormat($model->start_time)),
        array('name'=>'prc_data.describe', 'label'=>'流程描述'),
        array('label'=>'流程状态', 'value'=>$state_text[$model->run_state]),
        array('name'=>'step_no', 'label'=>'流程所处步骤')
    ),
)); ?>
<!--    流程的进程动态走向  -->
<table class="well detail-view table table-striped table-condensed">
<thead>
    <tr>
    <td></td>
        <!-- <td>步骤序号</td> -->
        <td>步骤名称</td>
        <td>步骤处理人</td>
        <td>步骤状态</td>
        <td>步骤开始时间</td>
        <td>步骤处理时间</td>
        <td>步骤反馈消息</td>
    </tr>
</thead>
<tbody>
    <?php
        $title = '流程具体走向';
        $prc_state_text = array('未结束','已结束');
        foreach ($model->run_prc as $key => $run_prc) {
            $state = $run_prc['handel_time'] ? 1 : 0;
            $start_time = $run_prc['start_time']>0 ? Tool::dateFormat($run_prc['start_time']): '----';
            $handel_time = $run_prc['handel_time']>0 ? Tool::dateFormat($run_prc['handel_time']): '----';
            $user_label = $users[$run_prc['step_user']];
            echo '<tr><th>',$title,'</th>',
            // '<td><span class="text-info">',$run_prc['step_no'],'</span></td>',
            '<td><span class="text-info">',$run_prc['step_name'],'</span></td>',
            '<td><span class="text-info">',$user_label,'</span></td>',
            '<td><span class="text-info">',$prc_state_text[$state],'</span></td>',
            '<td><span class="text-info">',$start_time,'</span></td>',
            '<td><span class="text-info">',$handel_time,'</span></td>',
            '<td><span class="text-info">',$run_prc['remark'],'</span></td>',
            '<tr>';
            $title = null;
        }
    ?>
    </tbody>
</table>