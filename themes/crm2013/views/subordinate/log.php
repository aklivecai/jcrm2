<?php
$this->breadcrumbs = array(
    Tk::g('Subordinate') => array(
        'index'
    ) ,
    Tk::g('Subordinate') ,
    Tk::g('Log') ,
);
$dls = array(
    Tk::g('Subordinate')
);
foreach ($this->users as $key => $value) {
    $dls[$key] = $value;
}
?>
<div class="row-fluid">
  <div class="span12">
  <div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('Log') ?></h1>   
  </div>
  <div class="block-fluid clearfix">
  <?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-form',
    'type' => 'search',
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));

echo $form->dropDownList($model, 'manageid', $dls);

echo $form->textFieldRow($model, 'info', array(
    'size' => 10,
    'maxlength' => 10
));

echo $form->textFieldRow($model, 'add_time', array(
    'size' => 10,
    'maxlength' => 10,
    'class' => 'type-date'
));

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g('Search')
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'button',
    'label' => Tk::g('Reset') ,
    'htmlOptions' => array(
        'class' => 'btn-reset'
    )
));
echo CHtml::button(Tk::g('Reset') , array(
    'type' => 'reset',
    'class' => 'hide'
));
?>
<?php $this->endWidget(); ?>   
<?php $widget = $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'list-grid',
    'dataProvider' => $model->search() ,
    'template' => "{items}",
    'enableHistory' => true,
    'loadingCssClass' => 'grid-view-loading',
    'summaryCssClass' => 'dataTables_info',
    'pagerCssClass' => 'pagination dataTables_paginate',
    'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
    'ajaxUpdate' => true, //禁用AJAX
    'enableSorting' => true,
    'summaryText' => '<span>共{pages}页</span> <span>当前:{page}页</span> <span>总数:{count}</span> ',
    'pager' => array(
        'header' => '',
        'maxButtonCount' => '5',
        'hiddenPageCssClass' => 'disabled',
        'selectedPageCssClass' => 'active disabled',
        'htmlOptions' => array(
            'class' => ''
        )
    ) ,
    'columns' => array(
        array(
            'name' => 'user_name',
            'type' => 'raw',
            'value' => 'implode(" - ",array($data->user_name,Yii::app()->getController()->users[$data->manageid]))',
            'headerHtmlOptions' => array(
                'style' => 'width: 180px'
            ) ,
        ) ,
        array(
            'name' => 'ip',
            'type' => 'raw',
            'value' => 'Tak::Num2IP($data->ip)',
            'headerHtmlOptions' => array(
                'style' => 'width: 85px'
            ) ,
        ) ,
        array(
            'name' => 'add_time',
            'value' => 'Tak::timetodate($data->add_time,5)',
            'headerHtmlOptions' => array(
                'style' => 'width: 120px'
            ) ,
        ) ,
        array(
            'name' => 'info',
            'type' => 'raw',
        ) ,
    ) ,
));
?>
  </div>
  </div>
</div>