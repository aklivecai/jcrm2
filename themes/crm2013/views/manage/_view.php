<?php
$att = array(
    'user_name',
    'user_nicename',
    array(
        'name' => 'branch',
        'type' => 'raw',
        'value' => $this->getBranch($model->branch)
    ) ,
    array(
        'name' => 'isbranch',
        'type' => 'raw',
        'value' => TakType::getStatus("isbranch", $model->isbranch)
    ) ,
    
    'user_email',
    array(
        'name' => 'add_time',
        'value' => Tak::timetodate($model->add_time, 6) ,
    ) ,
    array(
        'name' => 'add_ip',
        'value' => Tak::Num2IP($model->add_ip) ,
    ) ,
    array(
        'name' => 'last_login_time',
        'value' => Tak::timetodate($model->last_login_time, 6) ,
    ) ,
    array(
        'name' => 'last_login_ip',
        'value' => Tak::Num2IP($model->last_login_ip) ,
    ) ,
    'login_count',
    array(
        'name' => 'user_status',
        'type' => 'raw',
        'value' => TakType::getStatus("status", $model->user_status)
    ) ,
    
    'note',
    array(
        'name' => 'active_time',
        'value' => Tak::timetodate($model->active_time, 6) ,
    ) ,
);
if (Tak::getAdmin()) {
    $att[] = 'manageid';
}

$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => $att,
)); ?>