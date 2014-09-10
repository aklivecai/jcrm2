<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Tk::g(\$model->sName)=>array('admin'),
	Tk::g('Admin'),
);\n";
?>
$items = Tak::getListMenu();
?>
<div class="row-fluid">
	<div class="span12">

	<div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo"<?php echo Tk::g('{$this->modelClass}')?>"?></h1>   
		<ul class="buttons">
		    <li>
		        <a href="#" class="isw-settings"></a>
<?php echo "<?php"; ?> 
				$this->widget('application.components.MyMenu',array(
				      'htmlOptions'=>array('class'=>'dd-list'),
				      'items'=> $items ,
				));
			?>      
		    </li>
		</ul>                                    
	</div>	

	<div class="block-fluid clearfix">
<?php echo "<?php \$this->renderPartial('//_search',array('model'=>\$model,)); ?>\n"; ?>
<?php echo "<?php"; ?> $widget = $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'id' => 'list-grid',
	'dataProvider'=>$model->search(),
	'template'=>"{items}",
	'enableHistory'=>true,
    'loadingCssClass' => 'grid-view-loading',
    'summaryCssClass' => 'dataTables_info',
    'pagerCssClass' => 'pagination dataTables_paginate',
    'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
    'ajaxUpdate'=>true,    //禁用AJAX
    'enableSorting'=>true,
    'summaryText' => '<span>共{pages}页</span> <span>当前:{page}页</span> <span>总数:{count}</span> ',
	'filter'=>$model,
	'pager'=>array(
		'header'=>'',
		'maxButtonCount' => '5',
		'hiddenPageCssClass' => 'disabled'
		,'selectedPageCssClass' => 'active disabled'
		,'htmlOptions'=>array('class'=>'')
	),
	'columns'=>array(
/*
		array(
			'name'=>'user_name',
			'type'=>'raw',
			'value'=>'CHtml::link($data->user_name,array("view","id"=>$data->itemid))',
		),	
*/		
<?php
$count=0;
$arr = array();
if ($this->tableSchema->columns['display']) {
	$arr[] = "
		array(
			'name' => 'display',
			'htmlOptions'=>array('style'=>'width: 50px'),
			'value'=>'TakType::getStatus(\"display\",\$data->display)',
			'type'=>'raw',
			'filter'=>TakType::items('display'), 
		)
	";
}
foreach($this->tableSchema->columns as $column)
{
	if (Tak::giiColAdmin($column->name)) {
		continue ;
	}
	if(++$count==7)
		$arr[] = "\t\t/*\n";
	$arr[] = "\t\t'".$column->name."'\n";
}
if($count>=7)
	$arr[] =  "\t\t*/\n";

echo join($arr,',');
?>
		array(
			 'class'=>'bootstrap.widgets.TbButtonColumn'
			  ,'header' => CHtml::dropDownList('pageSize'
					,Yii::app()->user->getState('pageSize')
					,TakType::items('pageSize')
					,array(
						'onchange'=>"$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})", 
					)
			  )
			  ,'htmlOptions'=>array('style'=>'width: 85px')
		),		
	),
)); 
?>
		</div>
	</div>
</div>
