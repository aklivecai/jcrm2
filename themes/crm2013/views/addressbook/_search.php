<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'search-form',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well'),
    'action' => Yii::app()->createUrl($this->route),
    'method'=>'get',
)); 

	$types = AddressGroups::model()->getList();
	if (count($types)>0) {
		$_arrs = array('全部');
		foreach ($types as $key => $value) {
			$_arrs[$key] = $value;
		}
		echo $form->dropDownListRow($model,'groups_id',$_arrs); 
	}else{
		$this->widget('bootstrap.widgets.TbButton', array(
	    'type'=>'primary',
	    'label'=>'还没有部门,点击录入',
	    'block'=>true,
	    'url' => Yii::app()->createUrl('AddressGroups/create',array('type'=>'product','returnUrl'=>Yii::app()->request->url)),
	 	)); 
 	}	
  echo $form->textFieldRow($model,'name',array('size'=>10,'maxlength'=>10)); 

  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>Tk::g('Reset'),'htmlOptions'=>array('class'=>'btn-reset'))); 
  echo CHtml::button(Tk::g('Reset'),array('type'=>'reset','class'=>'hide'));
?>
<?php $this->endWidget(); ?> 


