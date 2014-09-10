<?php
$this->breadcrumbs = array(
    Tk::g('Subordinate') => array(
        'index'
    ) ,
    Tk::g('Subordinate') ,
    Tk::g('Order') ,
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

echo $form->dropDownList($model, 'itemid', $dls);
echo $form->textFieldRow($model, 'company', array(
    'size' => 10,
    'maxlength' => 50
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g('Search')
));
?>
<?php $this->endWidget(); ?>   
<?php $widget = $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'list-grid',
    'dataProvider' => $tags,
    'template' => "{items}",
    'enableHistory' => true,
    'loadingCssClass' => 'grid-view-loading',
    'summaryCssClass' => 'dataTables_info',
    'pagerCssClass' => 'pagination dataTables_paginate',
    'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
    'ajaxUpdate' => true, //禁用AJAX
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
            'name' => '操作',
            'value' => 'Yii::app()->getController()->getLinkOrderUser($data[itemid])',
            'type' => 'raw',
            'headerHtmlOptions' => array(
                'style' => 'width: 120px'
            ) ,
        ) ,
        array(
            'name' => '公司',
            'value' => '$data[company]',
            'type' => 'raw',
            'headerHtmlOptions' => array(
                'style' => 'width: 180px'
            ) ,
        ) ,
        array(
            'name' => '联系人',
            'value' => '$data[user_nicename]',
            'type' => 'raw',
            'headerHtmlOptions' => array(
                'style' => 'width: 85px'
            ) ,
        ) ,
        array(
            'name' => '手机',
            'value' => '$data[mobile]',
            'headerHtmlOptions' => array(
                'style' => 'width: 120px'
            ) ,
        ) ,
        array(
            'name' => '地址',
            'value' => '$data[address]',
            'type' => 'raw',
        ) ,
    ) ,
));
?>
  </div>
  </div>
</div>