<?php
/* @var $this ContactpPrsonController */
/* @var $model ContactpPrson */

$this->breadcrumbs = array(
    Tk::g('Contactp Prsons') => array(
        'admin'
    ) ,
);
$items = Tak::getViewMenu($model->itemid);
$items['Create']['url'] = array(
    'create',
    'ContactpPrson[clienteleid]' => $model->clienteleid
);

$_itemis = array(
    '---',
    'viewCompay' => array(
        'label' => Tk::g(array(
            'View',
            'Clientele'
        )) ,
        'url' => array(
            'Clientele/view',
            'id' => $model->clienteleid
        )
    ) ,
    'viewContact' => array(
        'label' => Tk::g(array(
            'View',
            'Contact'
        )) ,
        'url' => array(
            'Contact/admin',
            'Contact[prsonid]' => $model->itemid,
            'Contact[clienteleid]' => $model->clienteleid
        )
    ) ,
    'createContact' => array(
        'label' => Tk::g(array(
            'Create',
            'Contact'
        )) ,
        'url' => array(
            'Contact/create',
            'Contact[prsonid]' => $model->itemid,
            'Contact[clienteleid]' => $model->clienteleid
        )
    ) ,
);
array_splice($items, count($items) - 2, 0, $_itemis);
?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'iClientele.clientele_name',
        'nicename',
        
        array(
            'name' => 'sex',
            'type' => 'raw',
            'value' => TakType::getStatus('sex', $model->sex) ,
        ) ,
        'department',
        'position',
        'email',
        'phone',
        'mobile',
        'fax',
        'qq',
        'address',
        array(
            'name' => 'last_time',
            'value' => Tak::timetodate($model->last_time, 6) ,
        ) ,
        array(
            'name' => 'add_time',
            'value' => Tak::timetodate($model->add_time, 6) ,
        ) ,
        array(
            'name' => 'modified_time',
            'value' => Tak::timetodate($model->modified_time, 6) ,
        ) ,
        'note',
    ) ,
)); ?>
</div>
<div class="span2">
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'list',
    'items' => $items,
));
?>
</div>
</div>
</div>