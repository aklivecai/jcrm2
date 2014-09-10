<?php
/* @var $this ManageController */
/* @var $model Manage */

$this->breadcrumbs=array(
	'个人资料',
);
?>
<div class="row-fluid">
<div class="span4">
    <div class="block-fluid ucard">

            <div class="info">                                                                
                <ul class="rows">
                    <li class="heading">账户信息</li>
<?php $this->widget('ext.TakDetailView', array(
	'data'=>$model,
	'tagName'=>null,
	'attributes'=>array(
		'user_name',
		array('name'=>'add_time', 'value'=>Tak::timetodate($model->add_time,6),),
		array('name'=>'active_time', 'value'=>Tak::timetodate($model->active_time,6),),
		array('name'=>'last_login_time', 'value'=>Tak::timetodate($model->last_login_time,6),),
		array('name'=>'last_login_ip', 'value'=>Tak::Num2IP($model->last_login_ip),),
		'login_count',

	),
)); ?>       
                </ul>                                                      
            </div>                        
    </div>
</div>
<div class="span8">    
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); 
?>
<?php echo $form->errorSummary($model); ?>              
<div class="block-fluid without-head">                        
    <div class="row-form clearfix" style="border-top-width: 0px;">
    <?php echo $form->textFieldRow($model, 'user_nicename', array('size'=>60,'maxlength'=>64)); ?>
</div><div class="row-form clearfix">
    <?php echo $form->textFieldRow($model, 'user_email', array('size'=>60,'maxlength'=>100)); ?>
</div><div class="row-form clearfix">
	<?php echo $form->textAreaRow($model, 'note', array('maxlength'=>255)); ?>
  </div>

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'submit', 'label'=>$model->isNewRecord ? Tk::g('Add') : Tk::g('Save'))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'reset', 'label'=>Tk::g('Reset'))); ?>
</div>
<?php $this->endWidget(); ?>                                            
                    </div>
</div>

