<?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-form',
    'type' => 'search',
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));

$branchs = array(
    '-1' => $model->getAttributeLabel('branch')
);
foreach ($this->branchs as $key => $value) {
    $branchs[$key] = $value;
}
?>
<?php
if (Tak::getAdmin()) {
    echo $form->textFieldRow($model, 'fromid', array(
        'class' => 'select-fromid',
        'size' => 10,
        'style' => 'width:150px'
    ));
}
?>
<?php
echo ' ';
/**默认查找启用的用户**/
if ($model->user_status==-1){

}elseif ($model->user_status != 0 || $model->user_status == '') {
    $model->user_status = 1;
}
echo ' ';
echo $form->dropDownList($model, 'user_status', TakType::sitems('status', '状态'));
?>
<?php
echo $form->dropDownList($model, 'branch', $branchs);
?>
<?php
echo $form->textFieldRow($model, 'user_name', array(
    'size' => 10,
    'maxlength' => 10,
    'style' => "width:85px;"
));
?>
<?php
echo $form->textFieldRow($model, 'user_nicename', array(
    'size' => 10,
    'maxlength' => 10,
    'style' => "width:85px;"
));
?>
<?php
$form->textFieldRow($model, 'last_login_time', array(
    'size' => 10,
    'maxlength' => 10,
    'class' => 'type-date'
));
?>
<?php
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