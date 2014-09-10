<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs = array(
    Tk::g('Clienteles') => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);
$items = Tak::getListMenu();
Tak::regScript('tak', 'isprintf = true;', CClientScript::POS_END);
?>
<div class="row-fluid">
	<div class="span12">
	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('Clienteles') ?></h1>   
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
<?php $this->renderPartial('//_search', array(
    'model' => $model
)); ?>

<?php $this->renderPartial('_search', array(
    'model' => $model
)); ?>

<?php
$options = Tak::gredViewOptions();
$options['dataProvider'] = $model->search();
$columns = array(
    array(
        'name' => 'clientele_name',
        'type' => 'html',
        'value' => '$data->getHtmlLink()',
    ) ,
    array(
        'name' => 'address',
        'type' => 'raw',
        'sortable' => false,
    ) ,
    array(
        'name' => 'last_time',
        'value' => 'Tak::timetodate($data->last_time,4)',
        'headerHtmlOptions' => array(
            'style' => 'width: 85px'
        ) ,
    ) ,
    array(
        'name' => 'industry',
        'header' => '类型',
        'value' => 'TakType::getStatus("industry",$data->industry)',
        'type' => 'raw',
        'filter' => TakType::items('industry') ,
        'headerHtmlOptions' => array(
            'style' => 'width: 45px'
        ) ,
    ) ,
);
$columns = array_merge_recursive(array(
    $options['columns']
) , $columns);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
		</div>
	</div>
</div>
