<?php $itemid = Ak::setSId($data->itemid); ?>
<tr id="<?php echo $itemid ?>">
	<td><?php echo $data->name ?></td>
	<td><?php echo $data->price ?></td>
	<td>
	<?php
$arr = array(
    'id' => $itemid,
    'name' => $data->name,
    'price' => $data->price,
    'type' => 'price',
    'nums' => 2,
);
echo JHtml::link('', 'javascript:;', array(
    'title' => Tk::g('Update') ,
    'class' => 'icon-pencil btn-edit',
    'data-json' => CJSON::encode($arr) ,
));
echo " ";
echo JHtml::link('', array(
    'saves',
    'id' => $id,
    'itemid' => $itemid,
    'action' => 'delprice',
) , array(
    'title' => Tk::g('Delete') ,
    'class' => 'icon-remove'
));
?>
	</td>
</tr>