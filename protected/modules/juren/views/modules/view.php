<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs = array(
    Tk::g('Modules') => array(
        'index'
    ) ,
    $model->primaryKey,
);

$this->menu = array_merge_recursive($this->menu, array(
    array(
        'label' => Tk::g('Update') ,
        'url' => array(
            'update',
            'id' => $model->primaryKey
        )
    ) ,
    array(
        'label' => Tk::g('Admin') ,
        'url' => array(
            'index'
        )
    ) ,
    array(
        'label' => Tk::g('Create') ,
        'url' => array(
            'create'
        )
    ) ,
));
?>


<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'moduleid',
        'name',
        'module',
        'listorder',
        'note',
        'status',
    ) ,
)); ?>
