<?php
$this->pageTitle=Yii::app()->name .'-'. Tk::g('Init');
$this->breadcrumbs=array(
	Tk::g('Init'),
);

Yii::app()->user->setFlash('info', '<strong>'.$msg['company'].'</strong> <br />欢迎使用 '.Yii::app()->name);

Yii::app()->user->setFlash('warning', Yii::app()->params['help']);


?>      

<div class="container">
<div class="form-signin">
<?php 
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'verticalForm',
    'htmlOptions'=>array('class'=>'well'),
)); 

 $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
   ?>

<?php echo $form->hiddenField($model,'fromid'); ?>
<?php echo $form->textFieldRow($model, 'username', array('class'=>'span3','autofocus'=>'autofocus')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'激活')); ?> 

<?php $this->endWidget(); ?>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?

        'alerts'=>array( // configurations per alert type
            'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
   ?>
</div>
</div>