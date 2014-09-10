<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
	Tk::g('Clienteles') => array('index'),
	Tk::g('Move'),
);

 $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'verticalForm',
    'htmlOptions'=>array('class'=>'well'),
));

  echo $form->errorSummary($model); 

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
				  echo $form->textField($model,'manageid',array('class'=>'select-manageid','data-select'=>'Manage','data-not'=>$uid,'size'=>10,'style'=>'width:150px')); 
				?>
				<?php echo $form->error($model,'manageid'); ?>
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