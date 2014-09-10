<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'search-form',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well'),
    'action' => Yii::app()->createUrl($this->route),
    'method'=>'get',
)); 

  echo $form->textFieldRow($model,'clienteleid',array('class'=>'select-clientele','style'=>'width:200px')); 

  echo $form->textFieldRow($model,'nicename',array('size'=>10,'maxlength'=>50)); 


  echo $form->textFieldRow($model,'last_time',array('size'=>10,'maxlength'=>10,'class'=>'type-date')); 

 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>Tk::g('Reset'),'htmlOptions'=>array('class'=>'btn-reset'))); 
  echo CHtml::button(Tk::g('Reset'),array('type'=>'reset','class'=>'hide'));
?>
<?php $this->endWidget(); ?> 