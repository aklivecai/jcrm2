<?php
$this->breadcrumbs = array(
    Tk::g(array(
        'Admin',
        'Modules'
    )) ,
);

$this->menu = array(
    array(
        'label' => Tk::g('Create') ,
        'url' => array(
            'create'
        )
    ) ,
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'list-grid',
    'dataProvider' => $model->search() ,
    'enableSorting' => false,
    'ajaxUpdate' => false,
    'enableHistory' => false,
    'columns' => array(
        array(
            'class' => 'CButtonColumn',
            'template' => '{view} {update}  {delete} <br />',
            'header' => CHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
                'onchange' => "$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})",
                'style' => 'width: 100px !important',
            )) ,
        ) ,
        array(
            'name' => 'moduleid',
            'headerHtmlOptions' => array(
                'style' => 'width:80px;'
            ) ,
        ) ,
        'name',
        'module',
        'listorder',
        'note',
    ) ,
)); ?>

