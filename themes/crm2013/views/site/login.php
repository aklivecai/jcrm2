<?php
$this->pageTitle=Yii::app()->name . ' - '.Tk::g('Login');
$this->breadcrumbs=array(
	Tk::g('Login'),
);
Yii::app()->user->setFlash('info', '<strong>'.$msg['company'].'</strong> <br />欢迎使用 '.Yii::app()->name);
Yii::app()->user->setFlash('warning', Yii::app()->params['help']);
?>      

<div class="container">
<div class="form-signin">
<div class="googleq-rcode">
<?php $this->widget('application.components.GoogleQRCode', array(
    'size' => 120,
    'content' => Yii::app()->request->hostInfo.Yii::app()->request->getUrl(),
    'htmlOptions' => array('alt'=> '手机登录','title' => '手机登录')
));
?>
</div>
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'verticalForm',
    'htmlOptions'=>array('class'=>'well'),
));

 $this->widget('bootstrap.widgets.TbAlert', array(
        'alerts'=>array( // configurations per alert type
            'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
 ?>



<?php echo $form->hiddenField($model,'fromid'); ?>
<?php echo $form->textFieldRow($model, 'username', array('class'=>'span3','autofocus'=>'autofocus')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
<?php echo $form->checkboxRow($model, 'rememberMe'); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'登录')); ?> 


<?php $this->endWidget(); ?>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'alerts'=>array( // configurations per alert type
            'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
   ?>
</div>
</div>