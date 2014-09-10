<?php
/* @var $this MovingsController */
/* @var $data Movings */

$model = Movings::model();
$model->initak($typeid);

$_type = strtolower($model->getTypeName().'-type');
$cates = TakType::items($_type);

$tags = ProductMoving::model()->getProductMovings($typeid,$product_id);

$template ="<table class=\"table\"> <thead> <tr> 
			<th>{$model->getAttributeLabel('numbers')}</th>
			<th>{$model->getAttributeLabel('enterprise')}</th>
			<th>{$model->getAttributeLabel('typeid')}</th>
			<th>数量</th>  
			<th>{$model->getAttributeLabel('time')}</th>
			<th>{$model->getAttributeLabel('us_launch')}</th>
			<th>{$model->getAttributeLabel('time_stocked')}</th>
			</tr> </thead> <tbody>{items}</tbody> </table>" ;

 $this->widget('bootstrap.widgets.TbListView', array(
		'dataProvider' => $tags ,
		'itemView'=>'//movings/_product_moving_list',
		'template'=>$template,
		'htmlOptions'=>array('class'=>''),
        'emptyText'=>'<tr><td colspan="7">没有数据!</td></tr>',
        'viewData'=>array('cates'=>$cates)
	)); 
?> 