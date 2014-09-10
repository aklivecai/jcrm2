<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->    
    <link rel="icon" type="image/ico" href="favicon.ico"/>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <?php Yii::app()->bootstrap->register(); ?>    
    <!-- Custom styles for this template -->
    <link href="<?php echo yii::app()->theme->baseUrl;?>/css/page.css" rel="stylesheet"/>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo yii::app()->theme->baseUrl;?>/js/html5shiv.js"></script>
      <script src="<?php echo yii::app()->theme->baseUrl;?>/js/respond.min.js"></script>
    <![endif]-->    
</head>
<body>
	<div class="container">
			<div id="content">
				<?php echo $content; ?>
			</div><!-- content -->
	</div>
</body>
</html>