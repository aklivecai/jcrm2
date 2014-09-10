<?php $this->breadcrumbs = array(
	Rights::t('core', 'Rights').''=>Rights::getBaseUrl(),
	Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']))),
); ?>

<div class="head clearfix">
    <i class="isw-documents"></i> <h1><?php echo Rights::t('core', 'Create :type', array(
		':type'=>Rights::getAuthItemTypeName($_GET['type']),
	)); ?></h1>
</div>    
<div class="createAuthItem">
	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>
</div>