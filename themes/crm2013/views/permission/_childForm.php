<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'search',
)); 
	 echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); 
	 $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Rights::t('core', 'Add'))); 
	 $this->endWidget(); 
 ?>