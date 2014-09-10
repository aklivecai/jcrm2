<?php
/* @var $this MovingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Movings',
);

$this->menu=array(
	array('label'=>'Create Movings', 'url'=>array('create')),
	array('label'=>'Manage Movings', 'url'=>array('admin')),
);
?>

<h1>Movings</h1>
<?php
/* @var $this MovingsController */
/* @var $data Movings */
$typeid = 1;
$model = Movings::model();
$model->initak($typeid);

$_type = strtolower($model->getTypeName().'-type');
$cates = TakType::items($_type);

$product_id = '44251638363518195';

$tags = ProductMoving::model()->getProductMovings($typeid,$product_id);

$value::model()->sort_time()->recently('clienteleid='.$model->itemid);

$this->renderPartial('//movings/_product_moving_list',array('typeid'=>$typeid,'product_id'=>$product_id)); 

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
	),true); 

?> 