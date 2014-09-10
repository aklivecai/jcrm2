<?php
$this->breadcrumbs = array(
	Tk::g(array($model->getModel(),'Import')) => array($model->getModel())
);
?>
<div class="hero-unit message">
	<h1>导入成功</h1>
	<p>
	<?php echo  $message ?>
	</p>
</div>