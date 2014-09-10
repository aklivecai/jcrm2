<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g('Clienteles') => array('Clienteles'),
	Tk::g('Move'),
);

 $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'verticalForm',
    'htmlOptions'=>array('class'=>'well'),
));

  echo $form->errorSummary($modelF); 

 ?>
 <div class="form">
 <div class="block-fluid ucard">
            <div class="info">
                <ul class="rows">
                <?php 
				echo '<li class="heading">'.(!$this->isAjax?Tk::g(array('Move','Clientele')):'').'</li>';
                ?>
			<li >
				<div class="title"><?php echo $model->getAttributeLabel('clientele_name')?>:</div> 
				<div class="text">&nbsp;<?php echo $model->clientele_name;?></div>
			</li>
			<li >
				<div class="title">归属:</div> 
				<div class="text">&nbsp;<?php echo $uname;?></div>
			</li>
			<li >
				<div class="title"><?php echo Tk::g('Move')?>:</div> 
				<div class="text">&nbsp;
				<?php 
				  echo $form->textField($modelF,'tMid',array('class'=>'select-ajax','data-select'=>'Subordinate','data-get'=>'Manage','data-not'=>$uid,'size'=>10,'style'=>'width:180px')); 
				?>
				<?php echo $form->error($modelF,'tMid'); ?>
				</div>
			</li> 
			<li >
				<div class="title"><?php echo $modelF->getAttributeLabel('note')?>:</div> 
				<div class="text">&nbsp;
				<?php 
				  echo $form->textArea($modelF,'note',array()); 
				?>
				<?php echo $form->error($modelF,'note'); ?>
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
<?php echo $form->hiddenField($modelF,'fMid'); ?>
<?php $this->endWidget(); ?>
</div>
</div>