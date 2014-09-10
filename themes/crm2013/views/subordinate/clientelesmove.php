<?php
/* @var $this MovingsController */
/* @var $model Movings */
$sname =Tk::g(array('Move','Subordinate','Clienteles'));
$this->breadcrumbs=array(
	Tk::g(array('Subordinate','Clienteles')) => array('clienteles'),
	$sname,
);

 $form=$this->beginWidget('CActiveForm',array(
    'id'=>'verticalForm',
));
 ?>
 <div class="form">
 <div class="block-fluid ucard">
            <div class="info">
                <ul class="rows">
                <?php 
				echo '<li class="heading">'.(!$this->isAjax?$sname:'').'</li>';
                ?>
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('fMid')?>:</div>  
				<div class="text">&nbsp;
				<?php 
				  echo $form->textField($model,'fMid',array('class'=>'select-ajax','data-select'=>'Subordinate','data-get'=>'Manage','data-notbyel'=>true,'size'=>10,'style'=>'width:180px')); 
				?>
				<?php echo $form->error($model,'fMid'); ?>
				</div>
			</li>    
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('tMid')?>:</div> 
				<div class="text">&nbsp;
				<?php 
				  echo $form->textField($model,'tMid',array('class'=>'select-ajax','data-select'=>'Subordinate','data-get'=>'Manage','data-notbyel'=>true,'size'=>10,'style'=>'width:180px')); 
				?>
				<?php echo $form->error($model,'tMid'); ?>
				</div>
			</li>       
 			<li >
				<div class="title"><?php echo $model->getAttributeLabel('note')?>:</div> 
				<div class="text">&nbsp;
				<?php 
				  echo $form->textArea($model,'note',array()); 
				?>
				<?php echo $form->error($model,'note'); ?>
				</div>
			</li>       
                </ul>
            </div>      

<div class="footer tar">
    <?php $this->widget('bootstrap.widgets.TbButton', array('type'=>'primary','buttonType'=>'submit', 'label'=>Tk::g('Move'))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>Tk::g('Close'),
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal','style'=>!$this->isAjax?'display:none':''),
    )); ?>    
</div>
<?php $this->endWidget(); ?>
</div>
</div>