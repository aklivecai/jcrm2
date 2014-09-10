<?php
/* @var $this ManageController */
/* @var $model Manage */
$this->breadcrumbs = array(
    Tk::g('Manages') => array(
        'admin'
    ) ,
    $model->getLinkName() ,
);
?>
<div class="block-fluid">
    <div class="row-fluid">
        <div class="span10">
            <?php $this->renderPartial('_view', array(
    'model' => $model,
)); ?>
        </div>
        <div class="span2">
            <?php
$items = Tak::getViewMenu($model->primaryKey);
// revoke-link
unset($items['Delete']);
// $items['Delete']['label'] = Tk::g('Lock');
// $items['Delete']['linkOptions']['class'] = 'revoke-link';
$_itemis = array(
    '---',
    'log' => array(
        'label' => Tk::g('AdminLog') ,
        'icon' => 'indent-left',
        'url' => array(
            'AdminLog/admin',
            'AdminLog[user_name]' => $model->user_name
        )
    ) ,
    
    array(
        'label' => Tk::g(array(
            'More',
            'Manages'
        )) ,
        'url' => '#',
        'icon' => 'list',
        'itemOptions' => array(
            'data-geturl' => $model->getLink(false, 'gettop') ,
            'class' => 'more-list'
        ) ,
        'submenuOptions' => array(
            'class' => 'more-load-info'
        ) ,
        'items' => array(
            array(
                'label' => '...',
                'url' => '#'
            ) ,
        )
    )
);
$nps = $model->getNP(true);
if (count($nps) > 0) {
    array_splice($_itemis, count($_itemis) , 0, Tak::getNP($nps));
}
array_splice($items, count($items) - 2, 0, $_itemis);
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'list',
    'items' => $items,
));
?>
        </div>
    </div>
</div>

<?php if (Tak::getSupplier($model->branch)) {
    $this->renderPartial('_supplier', array(
        'model' => $model
    ));
} else {
    $this->renderPartial('_permission', array(
        'formModel' => $formModel,
        'assignSelectOptions' => $assignSelectOptions,
        'dataJurisdiction' => $dataJurisdiction,
        'subusers' => $subusers,
        'model' => $model,
    ));
}
?>