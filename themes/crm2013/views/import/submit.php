<script type="text/javascript">
<?php
$str = "";
if (count($model->errors) > 0) {
    $str = sprintf("parent.showError(%s);", CJSON::encode($model->errors));
} elseif ($model->script != null) {
    $str = $model->script;
} else {
    $str = ("parent.showOk()");
}
echo $str;
?>
</script>