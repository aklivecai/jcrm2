<?php
echo JHtml::tag('h1', sprintf('智能表单设计器 - 《%s》', $model->flow_name));
$ueditDir = 'ueditor/';
$scrpitS = array(
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static');
$this->regScriptFile(array(
    $ueditDir . 'ueditor.config-crm.js',
    $ueditDir . 'ueditor.all.min.js',
    $ueditDir . 'crmFormDesign/crmFormDesign.js',
    $ueditDir . 'crmFormDesign/crmFormDesignInit.js',
    'k-load-flow-settingform.js',
));
$this->beginWidget('CActiveForm', array(
    'id' => 'submit-form',
));
?>

<table width="100%">
    <tbody>
        <tr>
            <td>
                <script id="editor" name="content" type="text/plain" style="width:100%;height:450px;"><?php echo $formInfo->forhtml; ?></script>
            </td>
            <th width="110px" style="vertical-align: top;">
                <ul class="list-worklow-buttons" >
                    <li>
                        <button type="button" class="btn" data-fun="inserttext">文本框</button>
                    </li>
                    <li>
                        <button class="btn" type="button" data-fun="inserttextarea">多行文本</button>
                    </li>
                    <li>
                        <button class="btn" type="button" data-fun="insertselect">下拉控件</button>
                    </li>
                    <li>
                        <button class="btn" type="button" data-fun="insertcheckbox">多选框</button>
                    </li>
                    <li>
                        <button class="btn" type="button" data-fun="insertradio">单选按钮</button>
                    </li>
                    <li class="hide">
                        <button class="btn" type="button" onclick="crmDesign.exec('crm_template')">模板</button>
                    </li>
                    <li>
                        <button class="btn" type="button" id="btn-preview">预览</button>
                    </li>
                    <li class="dr"></li>
                    <li>
                        <button class="btn btn-info btn-save" type="button">保存</button>
                    </li>
                    <li>
                        <button class="btn btn-success btn-save" type="button" data-value="close">保存并关闭</button>
                    </li>
                    <li>
                        <button class="btn btn-danger" type="button" id="btn-close">关闭</button>
                    </li>
                </ul>
            </th>
        </tr>
    </tbody>
</table>
<?php
$this->endWidget();
$js = <<<END
var list = $('.btn').prop('disabled', 'disabled');
END;
Tak::regScript('init-edit', $js, CClientScript::POS_END);
?>
