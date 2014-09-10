<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-06 16:40:29
 * @version $Id$
 */
echo JHtml::tag('h1', sprintf('条件设置 - 《%s》', $model->step_name));
$js = sprintf('postUrl="%s",', $this->createUrl('StepCondition', array(
    'id' => $id,
    'itemid' => $itemid,
)));
$msg = array(
    'types' => $types,
    'files' => $files,
    'list' => $list,
);
$js.= ' msg=' . CJSON::encode($msg);
$js.= ';';
Tak::regScript('script-condition', $js, CClientScript::POS_HEAD);

$scrpitS = array(
    '_ak/js/advanced/knockout-latest.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-flow-steps-condition.js?201',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
?>
<div id="wap-condition">
    <table class="itable bordered">
        <colgroup>
            <col width="20%" />
            <col width="auto" />
        </colgroup>
        <tbody >
        	<tr>
        		<th>控件列表:</th>
        		<td>
        		<select  data-bind="options: files, optionsText: 'value', value: file" style="width:85px;"></select>
        		&nbsp;
        		  <span>条件:<select data-bind="options:types,optionsText: 'value',value:type" style="width:65px;"></select></span>
        		  &nbsp;        		  
        		  <span>值:<input type="text" data-bind='value:val, valueUpdate: "afterkeydown"' style="width:85px"/></span>
        		  &nbsp;
        		  <button class="btn btn-small btn-info" data-bind="click:addItem,enable:isAdd()">添加</button>
        		</td>
        	</tr>
        	<tr>
        		<th>条件公式:</th>
        		<td>
			    <select multiple="multiple" height="15" data-bind="options:allItems, optionsText: 'value',selectedOptions:selectedItems" style="width:100%;height:130px"></select> 
				    <button class="btn btn-danger btn-small" data-bind="click: removeSelected, enable: selectedItems().length > 0">删除</button>
        		</td>        		
        	</tr>
        </tbody>
    </table>
</div>