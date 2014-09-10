<?php
$this->breadcrumbs = array(
    Tk::g('Wage') => array(
        'Index'
    ) ,
    Tk::g('Admin') ,
);

$items = array(
    'Create' => array(
        'icon' => 'isw-plus',
        'url' => array(
            'Create'
        ) ,
        'label' => Tk::g('工时录入') ,
    ) ,
    'Summary' => array(
        'icon' => 'isw-list',
        'url' => array(
            'Count'
        ) ,
        'label' => Tk::g('Summary') ,
    )
);
?>

<div class="row-fluid">
    <div class="span12">
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo Tk::g('Wage') ?></h1>
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
$this->renderPartial('_search', array(
    'model' => $model
));

$listOptions = Tak::gredViewOptions(false);

$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
    array(
        'name' => 'name',
    ) ,
    array(
        'name' => 'order_time',
        'type' => 'raw',
        'headerHtmlOptions' => array(
            'style' => 'width: 60px'
        ) ,
        'value' => 'Tak::timetodate($data->order_time,3)'
    ) ,
    array(
        'name' => 'serialid',
    ) ,
    array(
        'name' => 'company',
    ) ,
    'product',
    'model',
    'standard',
    'color',
    'unit',
    array(
        'name' => 'amount',
        'value' => 'Tak::getNums($data->amount)',
    ) ,
    'name' => 'process',
    array(
        'name' => 'price',
        'value' => 'Tak::getNums($data->price)',
    ) ,
    array(
        'name' => 'sum',
        'value' => 'Tak::getNums($data->sum)',
    ) ,
    array(
        'name' => 'complete_time',
        'type' => 'raw',
        'headerHtmlOptions' => array(
            'style' => 'width: 60px'
        ) ,
        'value' => 'Tak::timetodate($data->add_time,3)'
    ) ,
    'note',
);

$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>
        </div>
    </div>
</div>