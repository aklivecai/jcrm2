<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g('Clienteles') => array('index'),
	$model->itemid,
);

$items = array();
foreach ($model->getNP(true) as $key => $value) {
  $items[] = array('icon'=>'isw-bookmark','label'=>Tk::g($key),'url'=>array('view','id'=>$value));
}
  $items[] = array('icon'=>'isw-left','label'=>Tk::g('Return'),'url'=>Yii::app()->request->urlReferrer);
  $items[] = array('url'=>'#','itemOptions'=>array('class'=>'toggle'));	
?>

<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
      <div class="isw-zoom"></div>
      <h1><?php echo Tk::g('Clienteles')?></h1>
    <?php 
    $this->widget('application.components.MyMenu',array(
          'htmlOptions'=>array('class'=>'buttons'),
          'items'=> $items ,
    ));
    ?>       
    </div>
    <div class="block" data-cookie='clienteles'>
  <?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'clientele_name',
		array('name'=>'rating','type'=>'raw', 'value'=>TakType::getStatus('rating',$model->rating),),
		array('name'=>'manageid','type'=>'raw', 'value'=>$model->iManage->user_nicename),
		array('name'=>'industry','type'=>'raw', 'value'=>TakType::getStatus('industry',$model->industry),),
		array('name'=>'profession','type'=>'raw', 'value'=>TakType::getStatus('profession',$model->profession),),
		array('name'=>'origin','type'=>'raw', 'value'=>TakType::getStatus('origin',$model->origin),),
		array('name'=>'employees','type'=>'raw', 'value'=>TakType::getStatus('employees',$model->employees),),
		'email',
		'address',
		'telephone',
		'fax',
		'web',
		array('name'=>'display','type'=>'raw', 'value'=>TakType::getStatus('display',$model->display),),
		array('name'=>'last_time', 'value'=>Tak::timetodate($model->last_time,6),),
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
		array('name'=>'modified_time', 'value'=>Tak::timetodate($model->modified_time,6),),
		'note',
	),
)); ?>
      </div>
    </div>
</div>
    <div class="dr"><span></span></div>
    <div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
      <div class="isw-chats"></div>
      <h1><?php echo Tk::g('Contact')?></h1>
      <ul class="buttons">
      <li class="toggle"><a href="#"></a></li>
      </ul>
    </div>
    <div class="block" data-cookie='clienteles-contact'>
      <?php
        $this->renderPartial('contact',array(
            'model' => $model,
            'mContact' => $mContact,
        )); 
      ?>
    </div>
    </div>    
    </div>