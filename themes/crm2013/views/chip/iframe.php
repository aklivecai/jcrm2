<?php
$str = "";
if ($script) {
    $str = $script;
} else {
    $str = ("parent.showOk()");
}
Tak::regScript('script-contente', $str, CClientScript::POS_LOAD);
?>