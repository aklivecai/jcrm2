<?php $itemid = Ak::setSId($data->itemid); ?>
<tr id="<?php echo $itemid ?>">
	<td><?php echo $data->name ?></td>
	<td>
	<?php
$arr = array(
    'id' => $itemid,
    'name' => $data->name,
    'type' => 'worker',
    'nums' => 1,
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
    'action'=>'delworker',
) , array(
    'title' => Tk::g('Delete') ,
    'class' => 'icon-remove'
));
?>
	</td>
</tr>