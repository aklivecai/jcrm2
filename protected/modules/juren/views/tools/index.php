<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'contact-form',
    'enableAjaxValidation' => false, //是否启用ajax验证
    'method' => 'post',
    'action' => array(
        'index'
    ) ,
    'htmlOptions' => array(
        'method' => 'get'
    ) ,
));
$msg = Tak::getFlash('msg', false);
if ($msg) {
    echo "<div class=\"flash success\">$msg</div>";
}
echo CHtml::dropDownList('action', $aciton, $this->actions);
echo CHtml::textField('fid', $fid, array(
    'list' => 'ids'
));
?>
<button type="submit">提交</button>
<datalist id="ids">
<?php foreach ($ids as $key => $value): ?>
<option value="<?php echo $key ?>" label="<?php echo $value ?>"/>
<?php
endforeach
?>
</datalist>
<?php
$this->endWidget();
?>
