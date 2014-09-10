<?php
$products = $data->getProducts();
?>
<tr>
    <td>
        <?php
foreach ($products as $value): ?>
<?php
    $rs = $value->attributes;
    $rs['company'] = $data->company;
    $rs['serialid'] = $data->serialid;
    $rs['add_time'] = Tak::timetodate($data->add_time, 5);
    $str = CJSON::encode($rs);    
    echo $rs['name'] ?>    
<?php
endforeach
?>
    </td>
    
    <td><?php echo $data->company ?></td>
    <td><?php echo $data->serialid ?></td>
    <td>
        <a class="btn btn btn-small" href='javascript:setProduct(<?php echo $str; ?>);'>选择
        </a>
    </td>
</tr>