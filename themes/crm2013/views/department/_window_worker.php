<?php $itemid = Ak::setSId($data->itemid); ?>
<tr>
	<td><?php echo $data->name ?></td>
	<td>
	<?php
$arr = array(
    'id' => $itemid,
    'name' => $data->name,
    'department_id' => Ak::setSId($data->department_id),
);
echo JHtml::link('选择',
	sprintf('javascript:setData(%s);',CJSON::encode($arr)),array(
    'title' => '选择',
    'class'=>'btn btn-small'
));
?>
	</td>
</tr>