<?php
$template = "<div class=\"list-view\">{pager}</div>\n<table class=\"items table table-striped table-bordered table-condensed\"> <thead> <tr> 
                <th>{$m->getAttributeLabel('numbers') }</th>
                <th>{$m->getAttributeLabel('warehouse_id') }</th>
                <th>{$m->getAttributeLabel('enterprise') }</th>
                <th>{$m->getAttributeLabel('typeid') }</th>
                <th>数量</th>  
                <th width='85'>{$m->getAttributeLabel('time') }</th>
                <th>{$m->getAttributeLabel('us_launch') }</th>
                <!--<th>{$m->getAttributeLabel('time_stocked') }</th>-->
                                        <th>{$m->getAttributeLabel('note') }</th>
                </tr> </thead> <tbody>{items}</tbody> </table>\n<div class=\"list-view\">{pager}</div>";
$this->widget('bootstrap.widgets.TbListView', array(
    'id' => $typeid,
    'dataProvider' => $tags,
    'itemView' => '//movings/_product_moving_list',
    'template' => $template,
    'htmlOptions' => array(
        'class' => ''
    ) ,
    'emptyText' => '<tr><td colspan="8">没有数据!</td></tr>',
    'viewData' => array(
        'cates' => $cates,
        'manageid'=>$model->add_us,
    )
) );

