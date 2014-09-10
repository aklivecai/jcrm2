<?php
$this->breadcrumbs = array(
    Tk::g('Order') => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);
$listMenu = array(
    'Wage' => array(
        'icon' => 'isw-user',
        'url' => array(
            '/Wage/Index'
        ) ,
        'label' => Tk::g(array('Wage','Admin')) ,
    ),
    'Delivery' => array(
        'icon' => 'isw-user',
        'url' => array(
            '/Printf/Delivery'
        ) ,
        'label' => Tk::g(array('Delivery','Admin')) ,
        'visible' => Tak::getFormid() == 5139,
    )
);
?>
<div class="row-fluid">
    <div class="span12">
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo Tk::g('Order') ?></h1>
            <?php
$this->widget('application.components.MyMenu', array(
    'htmlOptions' => array(
        'class' => 'buttons'
    ) ,
    'items' => $listMenu,
));
?>
        </div>
        <div class="block-fluid clearfix">
            <?php $this->renderPartial('_search', array(
    'model' => $model
));

$listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
    array(
        'type' => 'raw',
        'value' => 'Yii::app()->getController()->getLink($data->primaryKey,$data->status)',
        /*'template' => ' <div style="font-weight: bold;text-align: center;line-height:55px;">{updates}</div> '
            <br />{addproduct} <br />{addorderinfo} <br />{addorderflow}
            ,
            'buttons' => array(
            'updates' => array(
            'label' => '马上处理',
            'url' => '$data->getLinkUP()',
            'options' => array(
            'style' => 'color: red;'
            )
            ) ,
            ) ,
        */
        'header' => CHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
            'style' => 'width: 85px',
            'onchange' => "$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})",
        )) ,
        'htmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
        'headerHtmlOptions' => array(
            'style' => 'width: 100px'
        ) ,
    ) ,
    /*
            array(
            'name' => 'itemid',
            'headerHtmlOptions' => array(
            'style' => 'width: 120px'
            ) ,
            ) ,
    */
    array(
        'name' => 'company',
    ) ,
    array(
        'name' => 'serialid',
    ) ,
    array(
        'name' => '订单产品',
        'type' => 'raw',
        'value' => '$data->wProductsTitle()'
    ) ,
    array(
        'name' => '预期交货日期',
        'type' => 'raw',
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
        'value' => 'Tak::timetodate($data->iOrderInfo->date_time,3)'
    ) ,
    array(
        'name' => 'total',
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
        'value' => 'Tak::format_price($data->total)',
        'htmlOptions' => array(
            'class' => 'price-strong'
        )
    ) ,
    array(
        'name' => 'add_time',
        'type' => 'raw',
        'headerHtmlOptions' => array(
            'style' => 'width: 100px'
        ) ,
        'htmlOptions' => array(
            'style' => 'width: 100px'
        ) ,
        'value' => 'Tak::timetodate($data->add_time,5)'
    ) ,
    array(
        'name' => 'status',
        'type' => 'raw',
        'sortable' => false,
        'headerHtmlOptions' => array(
            'style' => 'width: 100px'
        ) ,
        'value' => '$data->getState()',
        'htmlOptions' => array(
            'class' => 'red '
        )
    )
);

$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>
        </div>
    </div>
</div>