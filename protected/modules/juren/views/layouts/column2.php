<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div class="span-20">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-4 last">
		<div id="sidebar">

<div class="portlet" id="yw2">
<div class="portlet-decoration">
<div class="portlet-title"><?php echo Tk::g('Test Memebers') ?></div>
</div>
<div class="portlet-content">
		<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu
));
?>
</div>
</div>


		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>