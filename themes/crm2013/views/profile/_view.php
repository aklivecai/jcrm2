<ul class="rows">
<?php $this->widget('ext.TakDetailView', array(
	'data'=>$model,
	'tagName'=>null,
	'attributes'=>array(
		'company',
		'user_nicename',
		'telephone',
		'mobile',
		'address',
		'fax',
	),
)); ?>
</ul>