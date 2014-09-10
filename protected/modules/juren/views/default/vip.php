<?php
$this->pageTitle=Yii::app()->name . ' - 提示';
$this->breadcrumbs=array(
  '提示',
);
?>


<?php if ($list):?>
	<h1>成功导入</h1>
<ol>
<?php foreach ($list as $model) :?>
<li>
<?php
	echo implode(' - ',array($model->company,$model->note));
 ?>
 </li>
<?php endforeach ?>
</ol>
<?php endif ?>


<?php if (!$list):?>
<h3>没有可导入的VIP会员</h3>
<?php endif ?>
<hr />

<h2>现有会员</h2>

<?php if (isset($tags)):?>
<ol>
<?php foreach ($tags as $model) :?>
<li>
<?php
	echo implode(' - ',array($model->company,$model->note));
	
 ?>
 </li>
<?php endforeach ?>
</ul>
<?php endif ?>