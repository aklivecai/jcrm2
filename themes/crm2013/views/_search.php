<?php
/* @var $this ClienteleController */
/* @var $model Clientele */
/* @var $form CActiveForm */
?>
<div class="row-fluid">

<?php 

$sdata = Tak::searchData();

$scol = isset($scol)?$scol:'add_time';
if (isset($condition)) {
	$scol = 'modified_time';
}

$isactive = isset($_GET['col'])&&isset($_GET['dt'])?$_GET['dt']:false;

$items = array(
	array('label'=>'全部', 'url'=>Yii::app()->createUrl($this->route),'active'=>!$isactive)
);



foreach ($sdata as $key => $value) {
	$items[$key] =  array('label'=>$value['name'], 'url'=>$url = Yii::app()->createUrl($this->route,array('col'=>$scol,'dt'=>$key)));
	if($isactive&&$isactive==$key&&isset($sdata[$isactive])){
		$items[$key]['active'] = true;
		$isactive = 0;
	}
}
if (is_numeric($isactive)&&$isactive!=0) {
	$items[0]['active'] = true; 
}

if (isset($subItems)&&is_array($subItems)) {
	$items = array_merge_recursive($items, $subItems);
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=> $items
));

?>


</div>
