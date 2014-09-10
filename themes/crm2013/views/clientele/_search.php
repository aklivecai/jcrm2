<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'search-form',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well'),
    'action' => Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<?php 
  echo $form->dropDownList($model,'industry',TakType::items('industry',0,'类型')); 
?>
<?php 
  echo $form->textFieldRow($model,'clientele_name',array('size'=>10,'maxlength'=>10)); 
?>
<?php 
  echo $form->textFieldRow($model,'last_time',array('size'=>10,'maxlength'=>10,'class'=>'type-date')); 
?>

<?php 
 if (Tak::getAdmin()) {
  echo $form->textFieldRow($model,'manageid',array('class'=>'select-manageid','size'=>20,'style'=>'width:150px')); 
 }
?>

<?php 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); 
  
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'button',
    'label' => Tk::g('More') ,
    'htmlOptions' => array(
        'class' => 'btn-more-serch'
    )
));
?>
<div id="list-more-search" class="hide">
<ul>
<li class="more-search-dlist">
<?php 
  echo $form->dropDownList($model, 'annual_revenue', TakType::items('annual_revenue',0,$model->getAttributeLabel('annual_revenue'))); 
  echo $form->dropDownList($model, 'origin', TakType::items('origin',0,$model->getAttributeLabel('origin')));
  echo $form->dropDownList($model, 'employees', TakType::items('employees',0,$model->getAttributeLabel('employees')));
  echo $form->dropDownList($model, 'profession', TakType::items('profession',0,$model->getAttributeLabel('profession')));
?>
</li>
  <li>
    <?php echo CHtml::activeLabelEx($model,'email')?>:
    <?php echo CHtml::activeTextField($model,'email');?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model,'address')?>:
    <?php echo CHtml::activeTextField($model,'address');?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model,'telephone')?>:
    <?php echo CHtml::activeTextField($model,'telephone');?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model,'add_time')?>:
    <?php echo CHtml::activeTextField($model,'add_time',array('class'=>'type-date'));?>
  </li>

</ul>
</div>
<?php $this->endWidget(); ?>   