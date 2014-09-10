<?php
$this->widget('zii.widgets.CMenu', array(
	'items'=>array(
		array(
			'label'=>'创建提醒', 
			'url'=>array('/post/create'), 
			'visible'=>Yii::app()->user->checkAccess('Post.Create')
		),
		array(
			'label'=>'退出系统', 
			'url'=>array('/site/logout'), 
			'visible'=>!Tak::isGuest()
		),
	),
));