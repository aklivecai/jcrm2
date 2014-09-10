<!DOCTYPE html>
<html>
<head>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel='stylesheet' type='text/css' href='<?php echo yii::app()->theme->baseUrl;?>/css/ak.base.css'/>
	<link rel='stylesheet' type='text/css' href='<?php echo yii::app()->theme->baseUrl;?>/css/ak.print.css' media='print' />
</head>
<body>

<div id="content">
	<?php echo $content; ?>
</div><!-- content -->
</body>
</html>