<?php
Tak::regScriptFile($this->createUrl('/iak/index') , '', null, CClientScript::POS_END);
$path = '_ak/js/plugins/select2/';
$scrpitS = array(
    '_ak/js/advanced/linq.min.js',
    '_ak/js/advanced/knockout-latest.js',
    '_ak/js/lib.js',
    '_ak/js/plupload/plupload.full.min.js',
    '_ak/js/plupload/i18n/zh_CN.js',
    $path . 'select2.min.js',
);

Tak::regCssFile($path . 'select2.min.css', 'static');

Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-cost-base.js?201',
    'k-load-cost-mvc.js?201',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
Tak::regScript('footer', ' var tags = [] ,ajaxUrl="' . $this->createUrl('select') . '", products = ' . CJSON::encode($products) . ' , uploadUrl = "' . $this->createUrl('/it/Upload') . '" ; ', CClientScript::POS_HEAD);
?>

<div id="wrapper">
    <form action="" method="post" id="form-const">
        <div class="mod" id="constac">
            <h2>物料成本清单核算</h2>
            <div class="modc">
                <div data-bind='foreach: {data:lines,afterRender:$root.initElement}'>
                    <table class="itable table-product" data-bind="attr:{id:getId('id')}">
                        <caption>
                            <div class="product-info">
                                <label>品 名:<input type="text" class="input-bborder" data-bind="value:type,attr:{name:getName('type')}" required/>
                                </label>
                                <label>型 号:<input type="text" class="input-bborder" data-bind="value:name,attr:{name:getName('name')}" required/>
                                </label>
                                <label>规格:<input type="text" class="input-bborder" data-bind="value:spec,attr:{name:getName('spec')}" required/>
                                </label>
                                <label>颜色:<input type="text" class="input-bborder" data-bind="value:color,attr:{name:getName('color')}" required/>
                                </label>

                                <label>制造管理费:<input type="number" class="expenses" value="0" data-bind="value: expenses,attr:{name:getName('expenses')}" step="any" />
                                </label>
                                <label>生产数量:<input type="number" class="input-bborder" value="1" data-bind="value:numbers,attr:{name:getName('numbers')}" required step="any" />
                                </label>
                                <label class="fbold">
                                    成本单价:￥
                                    <input type="text" readonly="readonly" value="0" class="text-show prices" data-bind="value:price,attr:{name:getName('price')}" tabIndex="-1" />
                                </label>
                                <label class="fbold">
                                    总成本:￥
                                    <input type="text" readonly="readonly" value="0" class="text-show" data-bind="value:totals,attr:{name:getName('totals')}" tabIndex="-1" />
                                </label>
                            </div>
                            <div class="fr">
                                <a class="icon action-deleted" title="删除">&nbsp;</a>
                                <a class="icon action-fold" title="折叠">&nbsp;</a>
                            </div>
                        </caption>
                        <colgroup align="center">
                            <col width="80px" />
                        </colgroup>
                        <tbody class="tbody-content">
                            <tr data-bind="template:{name: 'materia-template', data:mainMaterias }"></tr>
                            <tr data-bind="template:{name: 'materia-template', data:subMaterias }"></tr>
                            <tr data-bind="template:{name: 'process-template', data:process ,afterRender:initUpload}"></tr>
                        </tbody>
                        <tfoot style="display:block;">
                            <tr >
                                <td style="">
                         
                                </td>
                            <td  style="padding:5px; text-align: right;">
                                        <input type="text" class="ajax-select"   placeholder="选择已经核算过的产品" style="width:550px;" />
                            </td>
                            </tr>
                        </tfoot>
                    </table>
                    <hr/>
                </div>
            </div>        
                                <label>核算单标题:<input type="text" name='M[name]' id="cname"  required value="<?php echo $orderid > 0 ? $orderid . '-订单' : date('YmdHis') ?>" style="width:250px;"/>
                                </label>           
            <div class="footer-action">
                <a tabindex="-1" class="ibtn ibtn-cancel">关闭窗口</a>
                <button class="ibtn" type="button" data-bind="click: add">添加产品</button>
                <button class="ibtn ibtn-ok" type="submit">保存</button>
            </div>
            <div class="wap-tips">
                <span class="tips_icon_help">
                    提示: 核算说明
                </span>
                <div class="tips-mod">
                    <ul>
                        <li>管理制造费:如本产品需要加人员管理,设备折旧或其他费用可统计在
                            <span class="text-show">制造管理费</span>中;</li>
                        <li>输入中,涉及数量,单价,为必填选项,不能为空或者0;</li>
                        <li>红色边框为必填选项,不能为空</li>
                        <li>上传文件支持: 格式为jpg,gif,png,jpeg,文件大小不要超过5M</li>
                    </ul>
                </div>
            </div>
        </div>
        <input type="hidden" id="itemid" value="<?php echo $orderid ?>" />
        <input type="hidden" name='M[totals]' data-bind="value:$root.totals" />
    </form>
</div>
<?php $this->renderPartial('_mvc'); ?>
<!--
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
-->
