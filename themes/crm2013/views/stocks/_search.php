<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'search-form',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well'),
    'action' => Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

<?php 
  echo $form->dropDownList($model,'typeid',TakType::items('product',true,'货物分类'));
?>
<?php 
  echo $form->textFieldRow($model,'name',array('size'=>10,'maxlength'=>10)); 
?>

<?php 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>Tk::g('Reset'),'htmlOptions'=>array('class'=>'btn-reset'))); 
  echo CHtml::button(Tk::g('Reset'),array('type'=>'reset','class'=>'hide'));
?>
<?php $this->endWidget(); ?>   