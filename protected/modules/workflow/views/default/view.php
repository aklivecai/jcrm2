<?php
echo JHtml::tag('h1', sprintf('查看工作流程 - 《%s》', $flowInfo->flow_name));
$scrpitS = array(
    '_ak/js/advanced/knockout-latest.js',
);

Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
Tak::regScriptFile('_ak/js/webuploader/webuploader.min.js', 'static', null, CClientScript::POS_END, array(
    'id' => 'webuploader',
    'data-post-url' => Yii::app()->createUrl('TakFile/Upload', array(
        'id' => Tak::setSId(50) ,
    )) ,
));
$scrpitS = array(
    'ueditor/crmFormDesign/crmFormDesignInit.js',
);
$this->regScriptFile($scrpitS, CClientScript::POS_HEAD, array());

$this->regCssFile(array(
    'flow/flow-info.css?t=201417',
))->regScriptFile(array(
    'k-load-mvc.js',
    'k-load-flow-info.js?t=201411'
) , CClientScript::POS_END);
?>
<div class="mod">
    <div class="mod-head">流程信息</div>
    <div class="mod-body">
        <table class="itable bordered">
            <colgroup>
                <col width="10%" />
                <col width="auto" />
                <col width="10%" />
                <col width="auto" />
            </colgroup>
            <tbody class="ibody-edit">
                <tr>
                    <th><?php echo $model->getAttributeLabel('title') ?></th>
                    <td align="top"><?php echo JHtml::encode($model->title) ?></td>
                    <th><?php echo $model->getAttributeLabel('status') ?></th>
                    <td align="top"><?php echo JHtml::encode($model->status) ?></td>
                </tr>
                <tr>
                    <th><?php echo $model->getAttributeLabel('user') ?></th>
                    <td align="top"><?php echo $model->userName ?></td>
                    <th><?php echo $model->getAttributeLabel('start_time') ?></th>
                    <td align="top"><?php echo Tak::timetodate($model->start_time, 6) ?></td>
                </tr>
                <tr>
                    <th>
                        <?php echo $model->getAttributeLabel('describe') ?>
                    </th>
                    <td colspan="3">
                        <?php echo JHtml::encode($model->describe) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
$htmlForm = $fowForm->forhtml;
$files = $fowForm->getFields();
$files_val = $model->getFieldValues();

$_htmls = array();
$strWap = '<span  id="%s">%s</span>';
$strFile = '<span class="wf_field_show" style="%s">%s</span>';
$jsAttr = array();
$val = null;
$attr = null;
foreach ($files as $value) {
    $val = '';
    $__id = $value->primaryKey;
    $val = isset($files_val[$__id]) ? $files_val[$__id] : null;
    if ($value->isShow()) {
        $strOdata = $value->odata;
        if ($value->otype == 'checkbox') {
            //清空默认值
            $strOdata = str_replace('"checked":true', '"checked":0', $strOdata);
        }
        $odata = CJSON::decode($strOdata);
        $odata['checked'] = 0;
        $odata['disabled'] = 1;
        
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
    //
    $_htmls[$value->html] = sprintf($strWap, $value->getSId() , $val);
}
$htmlForm = strtr($htmlForm, $_htmls);

Tak::regScript('files-data', 'var showData=true; files_data = ' . CJSON::encode($jsAttr) . ';', CClientScript::POS_HEAD);
?>
<div class="mod">
    <div class="mod-head">表单信息</div>
    <div class="iform-wap">
        <div class="iform-content load-data " id="form-content">
            <?php echo $htmlForm ?>
        </div>
    </div>
</div>

<?php
$this->renderPartial('_files', array(
    'model' => $model
)); ?>
<div class="flow-tools-wap">
    <div class="flow-tools-body">
    <div class="wap-btns">
        <!-- <button class=" btn">打印</button> -->
        <!-- 流程完了以后才能打印 -->
        <!-- 流程步骤 -->
        <a class=" btn btn-warning target-win" data-width="800" data-height="300" href="<?php echo $this->createUrl('ViewRun', array(
    'id' => $id
)) ?>">流程步骤</a>
    </div>
    </div>
</div>
