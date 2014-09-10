<?php
$this->breadcrumbs = array(
    Tk::g('Subordinate') => array(
        'index'
    ) ,
    Tk::g(array(
        'Clienteles',
        'Admin'
    )) ,
);

$items = array(
    'moves' => array(
        'icon' => 'isw-sync',
        'url' => array(
            'ClientelesMove'
        ) ,
        'label' => Tk::g('Move') ,
        'linkOptions' => array(
            'class' => 'data-ajax',
            'title' => Tk::g(array(
                'Subordinate',
                'Clienteles',
                'Move'
            ))
        ) ,
    ) ,
);
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
<div class="row-fluid">
<?php
$isactive = $model->manageid;
$items = array(
    array(
        'label' => '全部',
        'url' => Yii::app()->createUrl($this->route) ,
        'active' => !$isactive
    )
);

foreach ($this->users as $key => $value) {
    $items[$key] = array(
        'label' => $value,
        'url' => Yii::app()->createUrl($this->route, array(
            $this->modelName . '[manageid]' => $key
        ))
    );
    if ($model->manageid && $model->manageid == $key) {
        $items[$key]['active'] = true;
        $isactive = 0;
    }
}

if (isset($subItems) && is_array($subItems)) {
    $items = array_merge_recursive($items, $subItems);
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked' => false, // whether this is a stacked menu
    'items' => $items
));
?>
</div>

<?php $this->renderPartial('_search', array(
    'model' => $model,
)); ?>
<?php
$listOptions = Tak::gredViewOptions(false);
$listOptions['dataProvider'] = $model->search();
$listOptions['columns'] = array(
    array(
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'template' => ' {show} | {move} ',
        'buttons' => array(
            'show' => array(
                'label' => '',
                'url' => 'Yii::app()->createUrl("Subordinate/ClientelesView", array("id"=>$data->primaryKey))',
                'options' => array(
                    'class' => 'icon-eye-open'
                ) ,
            ) ,
            'move' => array(
                'label' => '',
                'url' => 'Yii::app()->controller->createUrl("Subordinate/ClienteleMove", array("id"=>$data->primaryKey))',
                'options' => array(
                    'title' => Tk::g(array(
                        'Move',
                        'Clientele'
                    )) ,
                    'class' => 'icon-share-alt data-ajax'
                ) ,
            ) ,
        ) ,
        'header' => CHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
            'onchange' => "$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})",
        ))
    ) ,
    array(
        'name' => 'clientele_name',
    ) ,
    array(
        'name' => 'manageid',
        'type' => 'raw',
        'value' => '$data->iManage->user_nicename',
        'headerHtmlOptions' => array(
            'style' => 'width: 65px'
        ) ,
    ) ,
    array(
        'name' => 'industry',
        // 'header'=>'类型',
        'htmlOptions' => array(
            'style' => 'width: 80px'
        ) ,
        'value' => 'TakType::getStatus("industry",$data->industry)',
        'type' => 'raw',
        'headerHtmlOptions' => array(
            'style' => 'width: 80px'
        ) ,
    ) ,
    array(
        'name' => 'address',
        'type' => 'raw',
        'sortable' => false,
    ) ,
    array(
        'header' => '最后联系',
        'name' => 'last_time',
        'value' => 'Tak::timetodate($data->last_time,6)',
        'headerHtmlOptions' => array(
            'style' => 'width: 80px'
        ) ,
    ) ,
    array(
        'name' => 'add_time',
        'value' => 'Tak::timetodate($data->add_time,6)',
        'headerHtmlOptions' => array(
            'style' => 'width: 80px'
        ) ,
    ) ,
);
$widget = $this->widget('bootstrap.widgets.TbGridView', $listOptions);
?>
		</div>
	</div>
</div>


<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
	$(document).on('k-over','#myModal',function(){
		$.fn.yiiGridView.update('list-grid');
	})
});
/*]]>*/
</script>