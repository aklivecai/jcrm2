<?php
$scrpitS = array(
    'ueditor/crmFormDesign/crmFormDesignInit.js',
);
$this->regScriptFile($scrpitS, CClientScript::POS_HEAD, array());

$htmlForm = $fowForm->forhtml;
$files = $fowForm->getFields();

if (!$model->isNewRecord) {
    $files_val = $model->getFieldValues();
} else {
    $files_val = array();
}
$files_attr = $step->getFieldsBySql();
$_htmls = array();
$strWap = '<span  id="%s">%s</span>';
$strFile = '<span class="wf_field_show" style="%s">%s</span>';
$jsAttr = array();
$val = null;
$attr = null;
foreach ($files as $value) {
    $val = '';
    $__id = $value->primaryKey;
    $attr = isset($files_attr[$__id]) ? $files_attr[$__id] : false;
    if ($attr && $attr['show'] == 1) {
        $val = isset($files_val[$__id]) ? $files_val[$__id] : null;
        if ($attr['write'] == 1) {
            $odata = CJSON::decode($value->odata);
            $odata['must'] = $attr['must'];
            $odata['id'] = $value->getSId();
            if ($val != null) {
                $odata['dvalue'] = $val;
            }
            $jsAttr[] = array(
                'otype' => $value->otype,
                'odata' => $odata,
            );
        } else {
            //只是显示值
            $val = sprintf($strFile, $value->style, $val == null ? '' : $val);
        }
    }
    $_htmls[$value->html] = sprintf($strWap, $value->getSId() , $val);
}
$htmlForm = strtr($htmlForm, $_htmls);

Tak::regScript('files-data', 'var files_data = ' . CJSON::encode($jsAttr) . ';', CClientScript::POS_HEAD);
?>
<div class="mod">
    <div class="mod-head">表单信息</div>
    <div class="iform-wap">
        <div class="iform-content load-data " id="form-content">
            <?php echo $htmlForm ?>
        </div>
    </div>
</div>
