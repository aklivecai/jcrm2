<?php
$scrpitS = array(
    '_ak/js/plugins/jq.dragsort/jquery.dragsort-0.5.1.min.js',
    '_ak/js/advanced/knockout-3.1.0.js',
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);

$scrpitS = array(
    'k-load-wage-workshop.js?tt123',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);

$tags = CJSON::encode(array_values($data));
Tak::regScript('footer', '
var  tags = ' . $tags . '
, saveUrl = "' . $this->createUrl('') . '/../"
;
', CClientScript::POS_HEAD);
?>
<div id="wrapper">
	<div class="kclear"></div>
	<div class="mod" id="wamp-wage-workshop">
		<h2><?php echo Tk::g(array(
    'Workshop',
    'Setting'
)) ?></h2>
		<div class="modc cost-view">
			<table class="itable vtable">
				<colgroup>
				<col width="8%"/>
				</colgroup>
				<thead>
					<tr>
						<th>车间</th>
						<th></th>
					</tr>
				</thead>
				<tbody data-bind='foreach: {data:lines}'>
					<tr data-bind="attr:{id:workshop_id}">
						<th>
							<strong data-bind="text: name,value: name "></strong><br />
							<a href="javascript:;" data-bind="click:$root.remove" class="ibtn btn-del">删除车间</a>
						</th>
						<td>
							<table class="talbe-th" width="100%">
								<colgroup align="center">
								<col width="80px"/>
								</colgroup>
								<tbody>
									<tr>
										<th>
											工序
										</th>
										<td>
											<div title="拖动可以排序">
												<ul class="list-dragsort list-wage-process"  data-bind=" template:{name:templateToUse, foreach: lines ,afterRender:init}">
												</ul>
											</div>
											<hr />
											<label>工序
											<input type="text" class="name-process" placeholder="工序"/></label>  <label>工价<input type="number" class="name-price" min="0" value="0" /></label>
											<button class="ibtn add-process" type="button" data-bind="click: add">添加工序</button>
										</td>
									</tr>
									<tr>
										<th>工人</th>
										<td>
											<div title="拖动可以排序" class="list-worker" data-bind="template:{name:templateToWage, foreach: wages}">
											</div>
											<hr />
											<input type="text" class="name-worker" placeholder="工人"/>
											<button class="ibtn add-worker" type="button" data-bind="click: addWage">添加工人</button>
										</td>
									</tr>
								</tbody>
							</table>

						</td>
					</tr>
				</tbody>
				<tfoot>
				<tr>
					<th colspan="2"><hr /></th>
				</tr>
				<tr>
					<th class="txt-right">
						<label for="workshop">车间名字</label>
					</th>
					<td>
						<input type="text" id="name-workshop" placeholder="车间"/>
						<button class="ibtn ibtn-ok" type="button" data-bind="click: $root.add">添加车间</button>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>

	</div>
	<div class="footer-action">
		<a tabindex="-1" class="ibtn ibtn-cancel" onclick="window.close()">关闭窗口</a>
	</div>
	<div class="wap-tips not-printf" >
		<span class="tips_icon_help">
		提示
		</span>
		<div class="tips-mod">
			<ul>
				<li>添加车间时候，车间名字不允许重复</li>
				<li>同一个车间不允许有重复的工序名称</li>
				<li>拖动工序实现排序</li>
				<li> 工序劲操作图标说明
					<ol>
						<li>
							<span title="修改工序" class="icos ico-ok" >&nbsp;</span>
							修改工序
						</li>
						<li>
							<span title="删除工序" class="icos ico-del" >&nbsp;</span>
							删除工序
						</li>
						<li>
							<span title="修改工序" class="icos ico-cancel" >&nbsp;</span>
							取消修改
						</li>
						<li>
							<span title="修改工序" class="icos ico-save" >&nbsp;</span>
							保存工序
						</li>
					</ol>
				</li>
			</ul>
		</div>
	</div>
</div>
<script id="editWage" type="text/html">
<li class="row-edit"><div>
<span class="icos ico-cancel" title="取消" data-bind="click: $parent.cancelWage">&nbsp;</span><input type="text" data-bind="value: name,attr:{itemid:itemid,name:name},valueUpdate: 'afterkeydown'" class="edit-worker" /><span title="保存" class="icos ico-save" data-bind="click: $parent.saveWage">&nbsp;</span></div>
<i class="kclear"></i>
</li>
</script>
<script id="itemsWage" type="text/html">
<li><div><span class="icos ico-del" title="删除工序" data-bind="click:$parent.removeWage">&nbsp;</span><input type="text" data-bind="value: name,attr:{'itemid':itemid}" disabled="disabled" readonly="readonly" class="id-worker"/><span title="修改工序" class="icos ico-ok" data-bind="click: $parent.editWage">&nbsp;</span></div>
<i class="kclear"></i>
</li>
</script>


<script id="editTmpl" type="text/html">
<li class="row-edit"><div>
	<span class="icos ico-cancel" title="取消" data-bind="click: $parent.cancel">&nbsp;</span><input type="text" data-bind="value: name,attr:{itemid:itemid,name:name},valueUpdate: 'afterkeydown'" class="edit-process" /> <label>工价</label><input type="number" data-bind="value: price" /><span title="保存" class="icos ico-save" data-bind="click: $parent.save">&nbsp;</span></div>
	<i class="kclear"></i>
</li>
</script>
<script id="itemsTmpl" type="text/html">
<li><div><span class="icos ico-del" title="删除工序" data-bind="click:$parent.remove">&nbsp;</span><input type="text" data-bind="value: name,attr:{'itemid':itemid}" disabled="disabled" readonly="readonly" class="id-process"/> <label for="">工价</label><input type="number" disabled="disabled" readonly="readonly"  data-bind="value: price" /><span title="修改工序" class="icos ico-ok" data-bind="click: $parent.edit">&nbsp;</span></div>
<i class="kclear"></i>
</li>
</script>