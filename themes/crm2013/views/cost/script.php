<script type="text/javascript">
<?php
$str = "";
if (count($errors) > 0) {
    $str = CJSON::encode($errors);
    $str = sprintf("parent.showError(%s);", $str);
} elseif ($script != null) {
    $str = $script;
} else {
    $str = ("parent.showOk()");
}
echo $str;
?>
</script>