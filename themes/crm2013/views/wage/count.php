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
    'Index' => array(
        'icon' => 'isw-list',
        'url' => array(
            'Index'
        ) ,
        'label' => Tk::g('Admin') ,
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
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => 'search',
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
    'action' => $this->createUrl($this->route) ,
    'method' => 'get',
));
echo $form->dropDownListRow($search, 'yea', $listY);

echo $form->textFieldRow($search, 'keyword', array(
    'size' => 10,
    'maxlength' => 10
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g('Search')
));
echo CHtml::link(Tk::g('Reset') , $this->createUrl($this->route) , array(
    'class' => 'btn'
));
?>
<?php $this->endWidget(); ?> 



<table class="table">
    <thead>
        <tr>
<?php
$a = array_fill(1, 12, '');
$cols = array_keys($a);
echo sprintf('<th>姓名</th><th>%s月</th><th>总合计</th>', implode("月</th><th>", $cols));
?>            
        </tr>
    </thead>
    <tbody>
    <?php if (count($data) > 0) {
    foreach ($data as $key => $value) {
        echo sprintf('<tr><td>%s</td><td>%s</td></tr>', $value['name'], implode("</td><td>", $value['data']));
    }
} else {
    echo '<tr><td colspan="14"><strong>没有相关数据!</strong></td></tr>';
}
?>
    
    
    </tbody>
</table>
<?php
if (count($data) > 0) {
    echo '<div class="pagination dataTables_paginate">';
    $this->widget('bootstrap.widgets.TbPager', array(
        'pages' => $pages,
    ));
    echo '</div>';
}
?>

        </div>
    </div>
</div>