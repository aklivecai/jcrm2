<?php
$this->pageTitle=Yii::app()->name . ' - 提示';
$this->breadcrumbs=array(
  '提示',
);

?>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
    'heading'=>'提示',
)); ?>
 <p>
 <br />
<?php 
	echo  Yii::app()->user->getFlash('info');
?>
</p> 

<?php if (isset($tags)):?>
<?php foreach ($tags as $model) :?>

<?php
	echo join(' - ',array($model->itemid,$model->company,$model->note));
	echo '<hr />';
 ?>
<?php endforeach ?>
<?php endif ?>

<?php $this->endWidget(); ?>