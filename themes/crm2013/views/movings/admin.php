<?php
/* @var $this MovingsController */
/* @var $model Movings */

$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);
$subItems = array(
    'label' => Tk::g('Entering') ,
    'url' => $this->createUrl('create') ,
    'icon' => 'plus'
);
$_subItems = array();
foreach ($this->cates as $key => $value) {
    $_subItems[] = array(
        'label' => $value,
        'url' => $this->createUrl('create', array(
            'Movings[typeid]' => $key
        )) ,
        'icon' => 'isw-text_document'
    );
}

$listMenu = array(
    'Create' => array(
        'icon' => 'isw-plus',
        'url' => array(
            'create'
        ) ,
        'label' => Tk::g('Entering') ,
        'items' => $_subItems,
        'submenuOptions' => array(
            'class' => 'dd-list'
        ) ,
    )
);
?>
<div class="row-fluid">
    <div class="span12">
    <div class="head clearfix">
        <div class="isw-grid"></div>
<h1><?php echo Tk::g($model->sName) ?></h1>   
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
<?php
$this->renderPartial("/movings/_search", array(
    'model' => $model,
    'cates' => $this->cates
));
// $cates = $this->cates;
$options = Tak::gredViewOptions(false);
$options['dataProvider'] = $model->search();
$columns = array(
    Tak::getAdminPageCol(array(
        'template' => '{view}',
    )) ,
    array(
        'name' => '产品',
        'type' => 'raw',
        'value' => 'Yii::app()->controller->writeProduct($data->getProducts())',
    ) ,
    array(
        'name' => '金额',
        'type' => 'raw',
        'value' => 'Tak::tagNum(Tak::format_price($data->getTotal()),"label-success")',
    ) ,
    array(
        'name' => 'warehouse_id',
        'value' => 'Warehouse::deisplayName($data->warehouse_id)'
    ) ,
    array(
        'name' => 'enterprise',
        'type' => 'raw',
        'sortable' => false,
        'header' => $model->getAttributeLabel("enterprise") ,
    ) ,
    'numbers',
    array(
        'name' => 'us_launch',
        'type' => 'raw',
        'sortable' => false,
        'headerHtmlOptions' => array(
            'class' => 'stor-date',
            'style' => "width:65px;"
        )
    ) ,
    'note',
    array(
        'name' => 'time',
        'type' => 'raw',
        'value' => 'Tak::timetodate($data->time)',
        'headerHtmlOptions' => array(
            'class' => 'stor-date',
            'style' => "width:65px;"
        ) ,
        'header' => $model->getAttributeLabel("time") ,
    ) ,
    array(
        'name' => 'time_stocked',
        'type' => 'raw',
        'value' => 'TakType::getStatus("isok",$data->isAffirm()?1:0)',
        'headerHtmlOptions' => array(
            'class' => 'stor-date',
            'style' => "width:25px;"
        ) ,
        'header' => Tk::g($model->sName) ,
    )
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
        </div>
    </div>
</div>
<?php
Tak::regScript('tak', 'isprintf = true;istoxls = true;', CClientScript::POS_END);
?>
