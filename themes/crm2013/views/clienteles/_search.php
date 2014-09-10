<?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-form',
    'type' => 'search',
    'htmlOptions' => array(
        'class' => 'well search-form'
    ) ,
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));

$_permissions = Permission::getList();

$permissions = array(
    '部门'
);
foreach ($_permissions as $key => $value) {
    $permissions[$key] = $value;
}

echo $form->dropDownList($model, 'industry', TakType::items('industry', 0, '类型'));

echo CHtml::dropDownList('permission', Tak::getQuery('permission') , $permissions, array(

    'onchange' => "$('#Clientele_manageid').select2('val', '')",
));

echo $form->textFieldRow($model, 'manageid', array(
    'class' => 'select-manageid',
    'size' => 20,
    'style' => 'width:150px',
    'data-selectby' => '#permission:branch',
));

echo $form->textFieldRow($model, 'clientele_name', array(
    'size' => 20,
    'maxlength' => 50
));

echo $form->textFieldRow($model, 'add_time', array(
    'size' => 10,
    'maxlength' => 10,
    'class' => 'type-date'
));

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g('Search')
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'button',
    'label' => Tk::g('Reset') ,
    'htmlOptions' => array(
        'class' => 'btn-reset'
    )
));
echo CHtml::button(Tk::g('Reset') , array(
    'type' => 'reset',
    'class' => 'hide'
));
?>
<?php $this->endWidget(); ?>   