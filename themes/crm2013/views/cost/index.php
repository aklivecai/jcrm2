<?php
$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'index'
    ) ,
    Tk::g('Admin') ,
);
$items = array(
    'Create' => array(
        'icon' => 'isw-grid',
        'url' => array(
            '/Cost/Create'
        ) ,
        'label' => Tk::g(array(
            'Cost',
            'Create'
        )) ,
        'linkOptions' => array(
            'class' => 'target-win',
            'data-width' => '1100',
            'target' => '_blank',
        ) ,
    ) ,
);

    Tak::regScript('createCost', $strScript, CClientScript::POS_READY);
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
        'htmlOptions' => array() ,
        'headerHtmlOptions' => array(
            'style' => 'width: 125px'
        ) ,
    ) ,
    array(
        'name' => '类型',
        'type' => 'raw',
        'value' => 'sprintf("<span class=\"label\">%s</span>",$data->status==1?"一般核算":"订单转入核算")',
    ) ,
    array(
        'name' => 'name',
    ) ,
    array(
        'name' => '核算的产品',
        'type' => 'raw',
        'value' => 'Yii::app()->controller->writeProduct($data->getProducts())',
    ) ,
    array(
        'name' => 'totals',
        'type' => 'raw',
        'value' => 'Tak::format_price($data->totals)'
    ) ,
    array(
        'name' => 'add_time',
        'value' => 'Tak::timetodate($data->add_time,4)',
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
    ) ,
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
        </div>
    </div>
</div>
