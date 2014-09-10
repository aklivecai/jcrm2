<?php $this->breadcrumbs = array(
	Rights::t('core', 'Rights').''=>Rights::getBaseUrl(),
	Rights::t('core', 'Generate items'),
); ?>
<div class="row-fluid block-fluid"  id="generator">
<div class="dr"><span></span></div>
		<?php $form=$this->beginWidget('CActiveForm'); ?>
		<div class="span8">
				<table class="items table table-striped table-bordered table-condensed">
					<tbody>
						<tr class="application-heading-row">
							<th colspan="3"><?php echo Rights::t('core', 'Application'); ?></th>
						</tr>
						<?php $this->renderPartial('_generateItems', array(
							'model'=>$model,
							'form'=>$form,
							'items'=>$items,
							'existingItems'=>$existingItems,
							'displayModuleHeadingRow'=>true,
							'basePathLength'=>strlen(Yii::app()->basePath),
						)); ?>
					</tbody>
				</table>
</div>
<div class="span3">
   				<?php echo CHtml::link(Rights::t('core', 'Select all'), '#', array(
   					'onclick'=>"jQuery('.table-bordered').find(':checkbox').attr('checked', 'checked'); return false;",
   					'class'=>'selectAllLink')); ?>
   				/
				<?php echo CHtml::link(Rights::t('core', 'Select none'), '#', array(
					'onclick'=>"jQuery('.table-bordered').find(':checkbox').removeAttr('checked'); return false;",
					'class'=>'selectNoneLink')); ?>
					<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Rights::t('core', 'Generate'))); ?>
			
		<?php $this->endWidget(); ?>
</div>
	</div>
