<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    Tak::timetodate($model->time) => array(
        'view',
        'id' => $id
    ) ,
    '修改单据信息',
);

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'verticalForm',
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
));
echo $form->errorSummary($model);
?>
 <div class="form">
 <div class="block-fluid ucard">
            <div class="info">
                <ul class="rows">
                <?php
echo '<li class="heading">' . (!$this->isAjax ? '修改单据信息' : '') . '</li>';
?>
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('numbers') ?>:</div> 
				<div class="text">&nbsp;
					<?php echo $form->textField($model, 'numbers') ?>
					<?php echo $form->error($model, 'numbers'); ?>
				</div>
			</li>
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('enterprise') ?>:</div> 
				<div class="text">&nbsp;
					<?php echo $form->textField($model, 'enterprise') ?>
					<?php echo $form->error($model, 'enterprise'); ?>
				</div>
			</li>
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('us_launch') ?>:</div> 
				<div class="text">&nbsp;
					<?php echo $form->textField($model, 'us_launch') ?>
					<?php echo $form->error($model, 'us_launch'); ?>
				</div>
			</li>
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('note') ?>:</div> 
				<div class="text">&nbsp;
					<?php echo $form->textField($model, 'note') ?>
					<?php echo $form->error($model, 'note'); ?>
				</div>
			</li>
                </ul>
            </div>                        

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'type' => 'primary',
    'buttonType' => 'submit',
    'label' => Tk::g('Save')
)); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'label' => Tk::g('Close') ,
    'url' => '#',
    'htmlOptions' => array(
        'data-dismiss' => 'modal',
        'style' => !$this->isAjax ? 'display:none' : ''
    ) ,
)); ?>    
</div>
<?php $this->endWidget(); ?>
</div>
</div>