<?php
/* @var $this StocksController */
/* @var $model Stocks */
$product = Product::model()->findByPk($model->product_id);
$this->breadcrumbs=array(
	Tk::g($model->sName) => array('index'),
	$product->name,
);
?>
<div class="">
<?php 
$_stocks = $model->getAttributeLabel('stocks');
$msgProduct = "<h3> ".$_stocks."：{$model->stocks}</h3>";

$msgProduct .= $this->renderPartial('//product/_view',array('model'=>$product),true); 

$items = array(
        'product' => array('label'=>Tk::g(array('Product','Detail')), 'content'=>$msgProduct),
        );
$product_id = $model->product_id;

$m = Movings::model();
foreach (array(1=>'purchase', 2=>'sell') as $key => $value) {	
	$m->initak($key);
	$_type = strtolower($m->getTypeName().'-type');
	$cates = TakType::items($_type);
	$tags = ProductMoving::model()->getProductMovings($key,$product_id);

	$template ="<div class=\"list-view\">{pager}</div>\n<table class=\"items table table-striped table-bordered table-condensed\"> <thead> <tr> 
				<th>{$m->getAttributeLabel('numbers')}</th>
				<th>{$m->getAttributeLabel('enterprise')}</th>
				<th>{$m->getAttributeLabel('typeid')}</th>
				<th>数量</th>  
				<th>{$m->getAttributeLabel('time')}</th>
				<th>{$m->getAttributeLabel('us_launch')}</th>
				<!--<th>{$m->getAttributeLabel('time_stocked')}</th>-->
				</tr> </thead> <tbody>{items}</tbody> </table>\n<div class=\"list-view\">{pager}</div>" ;
	$content = $this->widget('bootstrap.widgets.TbListView', array(
			'dataProvider' => $tags ,
			'itemView'=>'//movings/_product_moving_list',
			'template'=>$template,
			'htmlOptions'=>array('class'=>''),
	        'emptyText'=>'<tr><td colspan="7">没有数据!</td></tr>',
	        'viewData'=>array('cates'=>$cates)
		),true); 	

$items[$value] = array('label'=>Tk::g(array(ucwords($value),'Detail')), 'content'=>$content);	
	
}

$isactive = isset($_GET['tab'])&&isset($_GET['tab'])?$_GET['tab']:false;
if ($isactive&&$items[$isactive]) {
	$items[$isactive]['active'] = true; 
}else{
	$items['product']['active'] = true; 
}
$this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs', // 'tabs' or 'pills'
     'placement'=>'above', // 'above', 'right', 'below' or 'left'
    'tabs' => $items,
)); 
?>
</div>