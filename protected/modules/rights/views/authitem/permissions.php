<?php $this->breadcrumbs = array(
	Rights::t('core', 'Rights').''=>Rights::getBaseUrl(),
	Rights::t('core', 'Permissions'),
); ?>
<?php if(!Yii::app()->request->isAjaxRequest): ?>
<div class="page-header">
<h1><?php echo Rights::t('core', 'Permissions'); ?>
<small><?php echo Rights::t('core', 'Here you can view and manage the permissions assigned to each role.'); ?></small></h1>
</div>
<?php endif; ?>

<div class="row-fluid" id="permissions">
<div class="head clearfix">
    <i class="isw-documents"></i> <h1><?php echo Tk::g(array('Update'));?></h1>
<ul class="buttons">
    <li>
        <?php echo CHtml::link('', array('authItem/generate'), array(
        'class'=>'isw-plus',
         'title'=>Rights::t('core', 'Generate items for controller actions')
    )); ?>
    </li>
</ul>       
</div>
<div class="block-fluid clearfix">
	<?php  $widget = $this->widget('bootstrap.widgets.TbGridView', array(
		'dataProvider'=>$dataProvider,
			'type'=>'striped bordered condensed',
			'template' => '{pager}{items}{pager}',
		'emptyText'=>Rights::t('core', 'No authorization items found.'),
		'htmlOptions'=>array('class'=>'grid-view permission-table'),
		'columns'=>$columns,
	)); ?>

	<p class="info">*) <?php echo Rights::t('core', 'Hover to see from where the permission is inherited.'); ?></p>

	<script type="text/javascript">

		/**
		* Attach the tooltip to the inherited items.
		*/
		jQuery('.inherited-item').rightsTooltip({
			title:'<?php echo Rights::t('core', 'Source'); ?>: '
		});

		/**
		* Hover functionality for rights' tables.
		*/
		$('#rights tbody tr').hover(function() {
			$(this).addClass('hover'); // On mouse over
		}, function() {
			$(this).removeClass('hover'); // On mouse out
		});

	</script>

</div>

</div>