<?php $itemid = Ak::setSId($data->itemid); ?>
<tr>
	<td><?php echo sprintf("%s - %s",$data->name,$data->price) ?></td>
	<td>
	<?php
$arr = array(
    'id' => $itemid,
    'name' => $data->name,
    'price' => $data->price
);
echo JHtml::link('选择',
	sprintf('javascript:setData(%s);',CJSON::encode($arr)),array(
    'title' => '选择',
    'class'=>'btn btn-small'
));
?>
	</td>
</tr>