<?php
$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'index'
    ) ,
    Tk::g('Admin') ,
);
$items = array(
    'Cproduct' => array(
        'icon' => 'isw-edit',
        'url' => array(
            '/Cost/CreateProduct'
        ) ,
        'label' => Tk::g(array(
            '新建产品成核算',
            'Create'
        )) ,
        'linkOptions' => array(
            'class' => 'target-win',
            'data-width' => '1100',
            'target' => '_blank',
        ) ,
    ) ,
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
        <?php
$this->widget('application.components.MyMenu', array(
    'htmlOptions' => array(
        'class' => 'buttons'
    ) ,
    'items' => $items,
));
?>      
    </div>
        <div class="block-fluid clearfix">
<?php
$this->renderPartial("_search_product", array(
    'model' => $model,
));
$options = Tak::gredViewOptions(false);
$options['dataProvider'] = $model->search();

$columns = array(
    array(
        'type' => 'raw',
        'value' => 'Yii::app()->getController()->getLinkProduct($data->itemid)',
        'header' => JHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
            'style' => 'width: 65px',
            'onchange' => "$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})",
        )) ,
        
        'headerHtmlOptions' => array(
            'style' => 'width: 65px'
        ) ,
    ) ,
    array(
        'name' => 'type',
    ) ,
    array(
        'name' => 'name',
    ) ,
    array(
        'name' => 'spec',
    ) ,
    array(
        'name' => 'color',
    ) ,
    array(
        'name' => 'expenses',
        'value' => 'Tak::getNums($data->expenses)',
    ) ,
    array(
        'name' => '成本',
        'value' => 'Tak::getNums($data->price)',
    ) ,
    array(
        'name' => 'add_time',
        'value' => 'Tak::timetodate($data->add_time,4)',
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
    ) ,
    array(
        'name' => '删除',
        'type' => 'raw',
        'value' => 'JHtml::link("", array("delProduct","id" => Tak::setSId($data->itemid)) , array("class" => "icon-remove"))',
        'headerHtmlOptions' => array(
            'style' => 'width: 25px'
        ) ,
    ) ,
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
        </div>
    </div>
</div>
