<?php $this->breadcrumbs = array(
	Rights::t('core', 'Rights').''=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments')=>array('assignment/view'),
	$model->getName(),
); ?>

<div class="page-header">
<h1><?php echo Rights::t('core', 'Assignments for :username', array(
		':username'=>$model->getName()
	)); ?>
</h1>
</div>

<div class="row-fluid" id="userAssignments">
<div class="head clearfix">
	<i class="isw-documents"></i> <h1><?php echo Tk::g(array('Update'));?></h1>
<ul class="buttons">
    <li>
        <a href="#" class="isw-settings"></a>
<?php
 $items = array();

if ($model->isNewRecord) {
    
}else{
  $items[] = array(
          'icon' =>'isw-zoom',
          'url' => array('/manage/view','id'=>$model->primaryKey),
          'label'=>Tk::g('View'),
        );
}

array_push($items
    ,array(
      'icon' =>'isw-refresh',
      'url' => Yii::app()->request->url,
      'label'=>Tk::g('Refresh'),
    )
    ,array(
      'icon' =>'isw-left',
      'url' => ''.Yii::app()->request->urlReferrer,
      'label'=>Tk::g('Return'),
    )
);


    $this->widget('application.components.MyMenu',array(
          'htmlOptions'=>array('class'=>'dd-list'),
          'items'=> $items ,
    ));
?>
    </li>
</ul>       

</div>
<div class="block-fluid clearfix">
	<div class="assignments span6">
		<?php  $widget = $this->widget('bootstrap.widgets.TbGridView', array(
			'dataProvider'=>$dataProvider,
			'type'=>'striped bordered condensed',
			'template' => '{pager}{items}{pager}',
			'hideHeader'=>true,
			'emptyText'=>Rights::t('core', 'This user has not been assigned any items.'),
			'htmlOptions'=>array('class'=>'grid-view user-assignment-table mini'),
			'columns'=>array(
    			array(
    				'name'=>'name',
    				'header'=>Rights::t('core', 'Name'),
    				'type'=>'raw',
    				'htmlOptions'=>array('class'=>'name-column'),
    				'value'=>'$data->getNameText()',
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
    				'value'=>'$data->getRevokeAssignmentLink()',
    			),
			)
		)); ?>

	</div>
<br />
    <div class="span5 add-assignment">
        <div class="block-fluid nm without-head">
            <div class="toolbar nopadding-toolbar clear clearfix">
                <h4><?php echo Rights::t('core', 'Assign item'); ?></h4>
            </div>                                  
        </div>
		<?php if( $formModel!==null ): ?>
			 <div class="block uploads">
				<?php $this->renderPartial('_form', array(
					'model'=>$formModel,
					'itemnameSelectOptions'=>$assignSelectOptions,
				)); ?>

			</div>
		<?php else: ?>
			<p class="info">
			<?php echo Rights::t('core', 'No assignments available to be assigned to this user.'); ?>
		<?php endif; ?>

	</div>
	</div>

</div>
