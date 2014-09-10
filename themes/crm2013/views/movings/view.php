<?php
/* @var $this MovingsController */
/* @var $model Movings */
$this->breadcrumbs = array(
Tk::g($model->sName) => array(
'admin'
) ,
);

$items = Tak::getViewMenu($model->itemid);
$items['Create']['label'] = Tk::g('Entering');

$subitems = array();

$warehouse = Warehouse::model()->findByAttributes(array(
'fromid' => $model->fromid,
'itemid' => $model->warehouse_id,
));

$tags = array(
'numbers',
'warehouse_id' => array(
'name' => 'warehouse_id',
'value' => $warehouse->name
) ,
'time' => array(
'name' => 'time',
'value' => Tak::timetodate($model->time)
) ,
'enterprise',
'us_launch',
'note',
'time_stocked' => array(
'name' => 'time_stocked',
'value' => Tak::timetodate($model->time_stocked, 6)
) ,
'add_time' => array(
'name' => 'add_time',
'value' => Tak::timetodate($model->add_time, 6) ,
) ,
'modified_time' => array(
'name' => 'modified_time',
'value' => Tak::timetodate($model->modified_time, 6) ,
) ,
);

$items['Update']['label'] = '修改单据信息';
$items['Update']['linkOptions'] = array(
'title' => $items['Update']['label'],
'class' => "data-ajax"
);
$items[] = array(
'label' => Tk::g('Print') ,
'icon' => 'print',
'url' => array(
'print',
'id' => $model->itemid
) ,
'linkOptions' => array(
'target' => '_blank'
)
);

$subitems['upproduct'] = array(
'label' => '修改产品明细',
'icon' => 'edit',
'url' => array(
'upproduct',
'id' => $id
)
);

/*是否已经确认操作*/
if ($model->isAffirm()) {
// unset($items['Update']);
unset($items['Delete']);
if (!$this->checkAccess()) {
unset($subitems['upproduct'] );
}
} else {
unset($tags['time_stocked']);
if (ProductMoving::model()->recentlyByMovingid($model->itemid)->count() > 0) {
# code...
$items['affirm'] = array(
'label' => Tk::g(array(
'Affirm',
$model->sName
)) ,
'icon' => 'ok-sign',
'url' => array(
'affirm',
'id' => $model->itemid
) ,
'linkOptions' => array(
'id' => 'btn-affirm'
)
);
}
}

array_splice($items, count($items) - 2, 0, $subitems);
// array_splice($items, count($items) - 2, 0, $_itemis);
//unset($tags['time_stocked']);

?>
<div class="block-fluid without-head">
    <div class="row-fluid ">
        <div class="toolbar nopadding-toolbar clear clearfix">
            <h4><?php echo Tk::g(array(
            $model->getTypeName() ,
            'bill'
            )); ?> (<?php echo $this->cates[$model->typeid] ?>)</h4>
        </div>
        <div class="dr"><span></span></div>
        <div class="span3">
            <?php
            $str = '<ul class="rows">
                <li class="heading">单据信息</li>';
                foreach ($tags as $key => $value) {
                if (is_numeric($key)) {
                $key = $value;
                $value = CHtml::encode($model->{$value});
                } else {
                $value = $value['value'];
                }
                // Tak::KD($tags,1);
                $str.= sprintf('<li><div class="title">%s:</div><div class="text">&nbsp;%s </div></li>', CHtml::encode($model->getAttributeLabel($key)) , $value);
                }
            $str.= '</ul>';
            echo $str;
            ?>
        </div>
        <div class="span6">
            <div class="block-fluid without-head">
                <div class="toolbar nopadding-toolbar clearfix">
                    <h4>产品明细</h4>
                </div>
                <?php $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $model->getProductMovings(8) ,
                // 'ajaxUpdate'=>false,
                'enableHistory'=>true,
                'itemsTagName' => 'tbody',
                'itemView' => '//movings/_product_view',
                'template' => '<table class="table"> <thead> <tr> <th>物料名字</th> <th>产品规格</th> <th>材料</th>  <th>颜色</th><th>价格</th><th>数量</th> <th>单位</th> </tr> </thead> {items} <tfoot><tr><td colspan="7">{summary}{pager}</td></tr></tfoot></table>',
                'htmlOptions' => array(
                'class' => ''
                ) ,
                'emptyText' => '<tr><td colspan="6">没有数据!</td></tr>'
                )); ?>
            </div>
        </div>
        <div class="span2">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'list',
            'items' => $items,
            ));
            ?>
        </div>
        <div class="dr"><span></span></div>
        <div class="tip-msg">
            <strong class="tip-title">提示</strong>
            <ol>
            <li>点击产品明细中对应的产品则显示该产品详细信息</li>
                <li>确认操作后产品明细不可以再修改，得通知管理员进行修改</li>

            </ol>
        </div>
    </div>
</div>