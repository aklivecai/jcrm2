<?php
$this->pageTitle=Yii::app()->name . ' - '.Tk::g('Error');
$this->breadcrumbs=array(
	Tk::g('Error'),
);
?>

<h2> <?php echo Tk::g('Error').$code; ?></h2>

<div class="error">
	<?php echo CHtml::encode($message); ?>
</div>