<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-06 16:40:29
 * @version $Id$
 */
echo JHtml::tag('h1', sprintf('表单控件 设置 - 第%s步  - 《%s》', $model->step_no, $model->step_name));
$js = sprintf('postUrl="%s",', $this->createUrl('StepFields', array(
    'id' => $id,
    'itemid' => $itemid,
)));
$js.= ' files=' . CJSON::encode($files);
$js.= ';';
Tak::regScript('script-fields', $js, CClientScript::POS_HEAD);

$scrpitS = array(
    '_ak/js/advanced/knockout-latest.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-flow-steps-fields.js?201',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
?>
<div id="wap-fields">
    <div>
        <p>
            操作:
            <button class="btn btn-info btn-save" type="button">保存</button>

            <button class="btn btn-success btn-save" type="button" data-value="close">保存并关闭</button>
        </p>
    <i class="dr"></i>
        设置:
        <button class="btn" type="button" data-bind="attr:{disabled:selects().length==0},click:setShow">可见</button>
        <button class="btn" type="button" data-bind="attr:{disabled:selects().length==0},click:setHide">保密</button>

        <button class="btn" type="button" data-bind="attr:{disabled:selects().length==0},click:setWrite">可用</button>
        <button class="btn" type="button" data-bind="attr:{disabled:selects().length==0},click:setNWrite">取消可用</button>

        <button class="btn" type="button" data-bind="attr:{disabled:selects().length==0},click:setMust">必填</button><button class="btn" type="button" data-bind="attr:{disabled:selects().length==0},click:setNMust">取消必填</button>
        <i class="dr"></i>
    </div>
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'mod-form',
)); ?>
    <table class="itable bordered">
        <colgroup>
            <col width="25px" />
            <col width="auto" />
            <col width="100px" />
            <col width="100px" />
            <col width="100px" />
        </colgroup>
        <thead>            
            <tr>
                <th><input type="checkbox" data-bind="checked: checkAll"/></th>
                <th>控件名称</th>
                <th>控件类型</th>
                <th>控件可见方式</th>
                <th>控件设置</th>
            </tr>
        </thead>
        <tbody data-bind="foreach: lines">
            <tr data-bind="css:{select:check}">
                <td>
                <input type="checkbox" value="1" data-bind="checked: check">
                <input type="hidden" data-bind="value:isShow,attr:{name:getName('show')}">
                </td>
                <td data-bind="text:name "></td>
                <td data-bind="text:type "></td>
                <td>
                    <label>
                            <input type="radio" value="1" data-bind="checked: isShow">
                            可见
                    </label>
                    <label>
                            <input type="radio" value="0" data-bind="checked: isShow">
                            保密
                    </label>
                </td>
                <td>                    
                    <label>
                            <input type="checkbox" value="1" data-bind="checked: isWrite,attr:{disabled: !ishow(),name:getName('write')}">
                            可用
                    </label>  
                    <i data-bind="text:iswite"></i>
                    <label>
                            <input type="checkbox" value="1" data-bind="checked: isMust,attr:{disabled: !ishow(),name:getName('must')}">
                            必填
                    </label>
                </td>
            </tr>
        </tbody>
    </table>
    <?php $this->endWidget(); ?>
</div>