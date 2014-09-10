<div class="control">
	<label for="warehouse_id">
	请选择导入到的业务员<span class="red">(*)</span>:
	</label>
<?php 
echo JHtml::textField('manageid', $_POST['manageid'], array(
    'id' => 'import-manageid',
    'class' => "select-manageid",
    'style' => 'width:180px;',
    'size' => 20
));
?>
</div>