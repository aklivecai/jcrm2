<?php
/** * 设置审批流程的步骤和表单 * @authors aklivecai (aklivecai@gmail.com) * @date 2014-08-07 14:51:44 * @version $Id$ */
$this->breadcrumbs[] = Tk::g(array(
    $model->flow_name,
    ' - ',
    "Setting",
));
Tak::regScriptFile($this->createUrl('/Manage/SelectAll') , '', null, CClientScript::POS_END);

$path = '_ak/js/plugins/select2/';
$scrpitS = array(
    '_ak/js/advanced/linq.min.js',
    '_ak/js/advanced/knockout-latest.js',
    $path . 'select2.min.js',
);

Tak::regCssFile($path . 'select2.min.css', 'static');

Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);

$scrpitS = array(
    'k-load-select.js',
    'k-load-mvc.js?201',
    'k-load-flow-steps.js?201',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);

echo JHtml::tag('h1', sprintf('流程设计 - 《%s》', $model->flow_name));

$list = $model->getFlowStepsBySql();

$js = sprintf(' var itemid ="%s",', $id);
$js.= sprintf('postUrl="%s",', $this->createUrl('FlowSteps', array(
    'id' => $id
)));
$js.= 'list=' . CJSON::encode($list);

$js.= ';';
Tak::regScript('script-steps', $js, CClientScript::POS_HEAD);
?>
<div id="wap-steps">
<table class="itable bordered">
    <colgroup>
        <col width="55px" />
        <col width="80px" />
        <col width="auto" />
        <col width="80px" />
        <col width="120px" />
        <col width="80px"/>
    </colgroup>
    <thead>
        <th>步骤</th>
        <th>名称</th>
        <th>处理人</th>
        <th>超时时间</th>
        <th>控件/条件</th>
        <th>操作</th>
    </thead>
     <tbody data-bind=" template:{name:templateToUse, foreach: lines,afterRender: initElement }">
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">                
            </td>
            <td>
                <button class="btn btn-info btn-small" data-bind="click: addI,disable: isNews()">添加</button>
            </td>
        </tr>
    </tfoot>
</table>
</div>
<script id="itemsTmpl" type="text/html"> 
<tr class="row-title" data-bind="attr: {title: step_name}">
    <td>第<span class="label" data-bind="text: $root.indexNumber($index())"></span>步</td>
    <td data-bind="text: step_name"></td>
    <td data-bind="text: step_user_name"></td>
    <td data-bind="text: timeout_name"></td>
    <td>

        <a data-bind="attr: {href: $root.getLink(itemid(),'f')}" class="target-win" data-width="500">表单控件</a>
    <!-- ko ifnot: isOne() -->
        <a data-bind="attr: {href: $root.getLink(itemid(),'c')}" class="target-win" data-width="500" data-height="250">条件</a>
        <!-- /ko -->
    </td>
    <td class="buttons">
        <span class="btn btn-mini" data-bind="click: $root.edit" title="修改"><i class="icon-edit"></i></span>
        <span class="btn btn-mini" data-bind="click: $root.removeObj,visible:!isOne(), attr: {id:getId('del') }"  title="remove"><i class="icon-remove"></i></span>
    </td>
</tr>
</script>

<script id="itemsTmplOne" type="text/html"> 
<tr data-bind="attr: {id:getId('id')}">
    <td>第<span class="label" data-bind="text: $root.indexNumber($index())"></span>步</td>
    <td><input data-bind="value: step_name,attr: {id:getId('step_name')} ,valueUpdate:'afterkeydown'"  type="text" /></td>
    <td data-bind="text: step_user_name"></td>
    <td data-bind="text: timeout_name"></td>
    <td></td>
    <td class="buttons">
        <span class="btn btn-mini" data-bind="click: $root.save" title="修改"><i class="icon-edit"></i></span>
        <span class="btn btn-mini" data-bind="click: $root.cancel"  title="取消"><i class="icon-trash"></i></span>
    </td>
</tr>
</script>
 <script id="editTmpl" type="text/html">
   <tr data-bind="attr: {id:getId('id')}">
      <td>第<span class="label" data-bind="text: $root.indexNumber($index())"></span>步</td>
      <td><input data-bind="value: step_name,attr: {id:getId('step_name')} ,valueUpdate:'afterkeydown'"  type="text" /></td>
      <td data-bind="attr:{id:getId('step_user')}"><input data-bind="value: step_user,valueUpdate:'afterkeydown'" class="stor-txt ajax-select"/></td>
       <td>
        <input data-bind="value: timeout,valueUpdate:'afterkeydown'" required  step="1" type="number" min="0"/>
       </td>
       <td></td>
        <td class="buttons">
            <span class="btn btn-mini btn-success" data-bind="click: $root.save,attr: {id:getId('save')}" title="保存"><i class="icon-ok"></i></span>
            <!-- ko if: isNews() -->
                <span class="btn btn-mini" data-bind="click: $root.removeI"  title="删除"><i class="icon-remove"></i></span>
            <!-- /ko -->
            <!-- ko ifnot: isNews() -->
                    <span class="btn btn-mini" data-bind="click: $root.cancel"  title="取消"><i class="icon-trash"></i></span>
            <!-- /ko -->            
            
        </td>
   </tr>
</script>