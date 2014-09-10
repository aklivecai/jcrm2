<?php
$this->breadcrumbs[] = Tk::g(array(
    'Workflow',
    'Setting'
));
$htmlCreate = Tk::g(array(
    'Create',
    'Workflow',
));
echo JHtml::link($htmlCreate, array(
    'action'
) , array(
    'title' => $htmlCreate,
    'class' => 'btn target-win',
    "data-width" => 320,
    "data-height" => 180
));

$listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
    array(
        'header' => '操作',
        'type' => 'raw',
        'value' => 'Yii::app()->getController()->getLink($data->getSId(),$data->status)',
        'headerHtmlOptions' => array(
            'style' => 'width: 300px'
        ) ,
    ) ,
    array(
        'name' => 'flow_name',
        'header' => '流程名称',
        'headerHtmlOptions' => array(
            'style' => 'width: 200px'
        ) ,
    ) ,
    array(
        'name' => 'note',
    ) ,
    array(
        'name' => 'status',
        'type' => 'raw',
        'value' => 'TakType::getStatus("workstatus",$data->status)',
        'headerHtmlOptions' => array(
            'style' => 'width: 40px'
        ) ,
    ) ,
);
$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>