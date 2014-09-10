<!DOCTYPE html>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php 
	$this->regCssFile('ak.base.css')->regCssFile('ak.print.css','print');
?>
</head>
<body>

<div id="content">
	<?php echo $content; ?>
</div><!-- content -->
</body>
</html>