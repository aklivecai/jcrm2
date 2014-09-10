<?php if (!$data): ?>
<div class="errory">
    没有完整的数据,或者上传的格式不正确!
<?php if ($errors): ?>
<div class="alert alert-block alert-error">
<p>请更正下列输入错误:</p>
<ul>
<?php foreach ($errors as $key => $value): ?>
    <li>
        <?php echo $value; ?>
    </li>
<?php
        endforeach
?>
</ul>
    </div>
<?php
    endif
?>
</div>
<?php
else: ?>
<?php
    $header = $model->getHeader();
    $html = $this->renderPartial('view', array(
        'header' => $header,
        'data' => $data,
        'action' => $action,
    ));
?>
<?php
endif
?>