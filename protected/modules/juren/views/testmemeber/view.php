<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs = array(
    Tk::g('Test Memebers') => array(
        'admin'
    ) ,
    $model->primaryKey,
);
if (Tak::getAdmin()) {
    $this->menu['db'] = array(
        'label' => Tk::g('Dbconfig') ,
        'url' => array(
            'db',
            'id' => $model->primaryKey
        )
    );
}

$this->menu = array_merge_recursive($this->menu, array(
    array(
        'label' => Tk::g('Update') ,
        'url' => array(
            'update',
            'id' => $model->primaryKey
        )
    ) ,
    array(
        'label' => Tk::g(array(
            'View',
            'Log'
        )) ,
        'url' => array(
            'testLog/admin',
            'TestLog[fromid]' => $model->primaryKey
        )
    ) ,
    
    array(
        'label' => Tk::g('Delete') ,
        'url' => '#',
        'linkOptions' => array(
            'submit' => array(
                'delete',
                'id' => $model->primaryKey
            )
        ) ,
    )
));

$img = $model->logo;
if ($img) {
    $img = CHtml::image($img, 'Logo', array(
        'style' => "max-width:200px"
    ));
} else {
    $img = '';
}
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'itemid',
        'company',
        'user_name',
        'email',
        array(
            'name' => '连接地址',
            'type' => 'raw',
            'value' => $model->getHtmlLink() ,
        ) ,
        array(
            'name' => 'active_time',
            'value' => Tak::timetodate($model->active_time, 6) ,
        ) ,
        array(
            'name' => 'start_time',
            'value' => Tak::timetodate($model->start_time, 6) ,
        ) ,
        
        array(
            'name' => 'add_time',
            'value' => Tak::timetodate($model->add_time, 6) ,
        ) ,
        
        array(
            'name' => 'add_ip',
            'value' => Tak::Num2IP($model->add_ip) ,
        ) ,
        array(
            'name' => 'modified_time',
            'value' => Tak::timetodate($model->modified_time, 6) ,
        ) ,
        'note',
        array(
            'name' => 'logo',
            'type' => 'raw',
            'value' => $img,
        ) ,
    ) ,
)); ?>
