<?php
$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'index'
    ) ,
    Tk::g('Admin') ,
);
?>
<div class="row-fluid">
    <div class="span12">
    <div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g(array(
    $this->modelName,
    'Admin'
)) ?></h1>   
    </div>
        <div class="block-fluid clearfix">
<?php
$this->renderPartial("_search", array(
    'model' => $model,
));
$options = Tak::gredViewOptions(false);
$options['dataProvider'] = $model->search();
$columns = array(
    array(
        'type' => 'raw',
        'value' => 'Yii::app()->getController()->getLink($data->itemid,$data->status)',
        'header' => JHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
            'style' => 'width: 85px',
            'onchange' => "$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})",
        )) ,
        'htmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
    ) ,
    array(
        'name' => 'name',
        'type' => 'raw',
    ) ,
    array(
        'name' => '生产的产品',
        'type' => 'raw',
        'value' => 'Yii::app()->controller->writeProduct($data->getProducts())',
    ) ,
    array(
        'name' => 'company',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'add_time',
        'value' => 'Tak::timetodate($data->add_time,6)',
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
    )
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
        </div>
    </div>
</div>