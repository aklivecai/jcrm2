<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs = array(
    Tk::g('Contact531') => array(
        'admin'
    ) ,
    $model->primaryKey,
);

$menus = array_merge_recursive($this->menu, array(
    array(
        'label' => Tk::g('Update') ,
        'url' => array(
            'update',
            'id' => $model->primaryKey
        )
    ) ,
    
    array(
        'label' => '正式转入',
        'url' => array(
            'toClientele',
            'id' => $model->primaryKey
        ) ,
        'linkOptions' => array(
            'class' => 'btn-confirm',
            "data-itemid" => $model->primaryKey
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

$nps = $model->getNP(true);

$nowDate = date("Y") . '-' . date("m") . '-' . date("d");

$lasttime = strtotime("$nowDate -1 day");

if (count($nps) > 0) {
    foreach ($nps as $key => $value) {
        $m = $this->loadModel($value, true);
        if ($m->add_time > $lasttime) {
            $menus[] = array(
                'label' =>  $key=='Pre'?'上一个':'下一个',
                'url' => array(
                    'view',
                    'id' => $m->primaryKey
                )
            );
        }
    }
}

$this->menu = $menus;
?>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'clientele_name',
        'nicename',
        'mobile',
        'phone',
        
        'address',
        'web',
        'business',
        
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
    ) ,
)); ?>
