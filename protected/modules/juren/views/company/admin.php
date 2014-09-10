<?php
/* @var $this TestLogController */
/* @var $model TestLog */

$this->breadcrumbs=array(
	'平台会员' => array('admin'),
);
?>

<?php echo CHtml::link(Tk::g('Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="<?php echo isset($_GET['search'])?'':'display:none'; ?>">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<hr />
<?php
	$get = Yii::app()->request->getQuery('t',0);
	echo JHtml::dropDownList('t',$get,TestCompany::$types,array('onchange'=>'window.location.href="'.Yii::app()->createUrl($this->route).'?t="+$(this).val();'));
?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'list-grid',
	'dataProvider' => $model->search(),
	'ajaxUpdate' => false,
	'enableHistory'=> true,
	'selectableRows'=>false,
	'columns'=>array(	
		array(
            	'header' => CHtml::dropDownList('pageSize'
                    ,Yii::app()->user->getState('pageSize')
                    ,TakType::items('pageSize')
                    ,array(
                        'onchange'=>"$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})", 
                    )  
               ) ,
			'name'=>'',
			'type'=>'raw',
			'value'=>'$data->getLinks()',        			
		),
		'userid',
		array(
			'name'=>'username',
			'type'=>'raw',
			'value'=>'CHtml::link($data->username,$data->linkurl,array("target"=>"_blank"))',
		),	
		'company',
		'vip',
		array(
			'name' => '时间',
			'value'=>'$data->getTime()',
			'type'=>'raw',
		),		
	),
)); ?>
