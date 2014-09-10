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
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Tk::g(\$model->sName) => array('admin'),
	\$model->{$nameColumn},
);
	\$items = Tak::getViewMenu(\$model->{$this->tableSchema->primaryKey});
?>\n";
?>

<div class="block-fluid">
	<div class="row-fluid">
	    <div class="span10">
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column){
	$cname = $column->name; 

	if(Tak::giiCol($cname)){
		continue ;
	}	
	$str = "\t\t";
	if(strpos($cname, 'time')>0){
		$str .= "array('name'=>':name', 'value'=>Tak::timetodate(\$model->:name),6)";
	}elseif(strpos($cname, 'ip')>0){
		$str .= "array('name'=>':name', 'value'=>Tak::Num2IP(\$model->:name),)";
	}elseif(strpos(',display,status,',$cname)>0){
		$str .= "array('name'=>':name','type'=>'raw', 'value'=>TakType::getStatus(':name',\$model->:name),)";
	}else{
		$str .= "':name'";
	}
	$str = str_replace(":name",$cname,$str);
	$str .= ",\n";
	echo $str;
}
?>
	),
)); ?>
</div>
<div class="span2">
<?php echo "<?php "; ?>
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list',
    'items'=> $items,
    )
); 
?>
</div>
</div>
</div>