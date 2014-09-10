<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php echo "<?php"; ?>
  $action = $model->isNewRecord?'Create':'Update';
 $items = Tak::getEditMenu($model-><?php echo "{$this->tableSchema->primaryKey}";?>,$model->isNewRecord);
?>
<div class="row-fluid">
<div class="span12">

<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	 'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<div class="head clearfix">
	<i class="isw-documents"></i><h1><?php echo "<?php"; ?> echo Tk::g(array('<?php echo $this->modelClass;?>',$action));?></h1>
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
<div class="block-fluid">
<?php

$strb = '';
$count = 0;
foreach($this->tableSchema->columns as $column)
{
	$cname = $column->name;
	if(Tak::giiColNot($cname)){
		continue ;
	}	
	$str = '';
	if(strpos($cname, 'time')>0){
		$str .= "";
	}elseif($cname == 'display'){
		$str .= "";
	}elseif($cname=='status'){
		$str .= "<?php echo CHtml::checkBox(':m[:name]',\$model->:name==1, array('class'=>'ibtn','value'=>1)); ?>";
	}else{
	}	
	if ($str!='') {
		$str = strtr($str,array(':name'=>$cname,':m'=>$this->modelClass));
		$str = "\t<div class=\"controls\">\n\t\t$str\n\t</div>\n";
		$strb += $str;
		continue ;
	}
	$count++;
?>
	<div class="row-form clearfix" <?php $count==1?'style="border-top-width: 0px;"':'' ?>>
		<?php 
			$str =  "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; 
			if($cname=='note'){
				$str = str_replace('textField','textAreaRow',$str);
			}
			else{
				$str = str_replace('textField','textFieldRow',$str);
			}
			echo  $str;
		?>
	</div>
<?php
	if ($strb!='') {
		echo $strb;
	}
}
?>

</div>

<div class="footer tar">
    <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'submit', 'label'=>Tk::g(\$action))); ?>\n
    <?php \$this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'reset', 'label'=>Tk::g('Reset'))); ?>
    \n"; ?>
</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
</div>
</div>