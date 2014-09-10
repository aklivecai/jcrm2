<?php $this->breadcrumbs = array(
	Rights::t('core', 'Rights').''=>Rights::getBaseUrl(),
	Rights::getAuthItemTypeNamePlural($model->type)=>Rights::getAuthItemRoute($model->type),
	$model->name,
); ?>

<div class="row-fluid" id="updatedAuthItem">
	<div class="span6">
		<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>
	</div>
	<div class="relations span6">
<div class="head clearfix">
 <div class="isw-cloud"></div>
 <h1><?php echo Rights::t('core', 'Relations'); ?></h1>
</div>	

<div class="block">
		<?php if( $model->name!==Rights::module()->superuserName ): ?>
			<div class="parents">
				<h4><?php echo Rights::t('core', 'Parents'); ?></h4>

				<?php  $widget = $this->widget('bootstrap.widgets.TbGridView', array(
					'dataProvider'=>$parentDataProvider,
					'type'=>'striped bordered condensed',
					'template'=>'{items}',
					'hideHeader'=>true,
					'emptyText'=>Rights::t('core', 'This item has no parents.'),
					'htmlOptions'=>array('class'=>'grid-view parent-table mini'),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Rights::t('core', 'Name'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLink()',
    					),
    					array(
    						'name'=>'type',
    						'header'=>Rights::t('core', 'Type'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
    					array(
    						'header'=>'&nbsp;',
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'actions-column'),
    						'value'=>'',
    					),
					)
				)); ?>

			</div>

			<div class="children">
				<h4><?php echo Rights::t('core', 'Children'); ?></h4>

				<?php  $widget = $this->widget('bootstrap.widgets.TbGridView', array(
					'type'=>'striped bordered condensed',
					'dataProvider'=>$childDataProvider,
					'template'=>'{items}',
					'hideHeader'=>true,
					'emptyText'=>Rights::t('core', 'This item has no children.'),
					'htmlOptions'=>array('class'=>'grid-view parent-table mini'),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Rights::t('core', 'Name'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLink()',
    					),
    					array(
    						'name'=>'type',
    						'header'=>Rights::t('core', 'Type'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
    					array(
    						'header'=>'&nbsp;',
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'actions-column'),
    						'value'=>'$data->getRemoveChildLink()',
    					),
					)
				)); ?>

			</div>

			<div class="addChild">
				<h5><?php echo Rights::t('core', 'Add Child'); ?></h5>
				<?php if( $childFormModel!==null ): ?>
					<?php $this->renderPartial('_childForm', array(
						'model'=>$childFormModel,
						'itemnameSelectOptions'=>$childSelectOptions,
					)); ?>
				<?php else: ?>
					<p class="info"><?php echo Rights::t('core', 'No children available to be added to this item.'); ?>
				<?php endif; ?>
			</div>

		<?php else: ?>

			<p class="info">
				<?php echo Rights::t('core', 'No relations need to be set for the superuser role.'); ?><br />
				<?php echo Rights::t('core', 'Super users are always granted access implicitly.'); ?>
			</p>

		<?php endif; ?>
		</div>

	</div>

</div>