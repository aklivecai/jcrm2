<?php
$scrpitS = array(
    '_ak/js/plugins/jq.dragsort/jquery.dragsort-0.5.1.min.js',
    '_ak/js/advanced/knockout-3.1.0.js',
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);

$scrpitS = array(
    'k-load-workshop.js',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
Tak::regScript('footer', '
var  tags = ' . CJSON::encode(array_values($data)) . '
, saveUrl = "' . $this->createUrl('') . '/../"
;
', CClientScript::POS_HEAD);
?>
<div id="wrapper">
<?php if (Yii::app()->request->urlReferrer && $dname != ''): ?>
<ul class="header-tools">
<li>
	<a href="<?php echo Yii::app()->request->urlReferrer ?>" class="ibtn">返回 - <?php echo $dname ?></a>
</li>
</ul>
<?php
endif
?>
<div class="kclear"></div>
	<div class="mod" id="wamp-workshop">
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
						<th class="txt-left">工序</th>
					</tr>
				</thead>
				<tbody data-bind='foreach: {data:lines}'>
					<tr data-bind="attr:{id:workshop_id}">
						<th>
							<strong data-bind="text: name,value: name" class="id-workshop"></strong><br />
							<a href="javascript:;" data-bind="click:$root.remove" class="ibtn btn-del">删除车间</a>
						</th>
						<td>
							<div title="拖动可以排序">
								<ul class="list-dragsort"  data-bind=" template:{name:templateToUse, foreach: lines ,afterRender:init}">
								</ul>
							</div>
							<hr />
							<input type="text" id="workshop" class="name-process" placeholder="工序"/>
							<button class="ibtn add-process" type="button" data-bind="click: add">添加工序</button>
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
		<a tabindex="-1" class="ibtn ibtn-cancel">关闭窗口</a>
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
<script id="editTmpl" type="text/html">
<li class="row-edit"><div>
<span class="icos ico-cancel" title="取消" data-bind="click: $parent.cancel">&nbsp;</span><input type="text" data-bind="value: name,attr:{itemid:itemid,name:name},valueUpdate: 'afterkeydown'" class="edit-process" /><span title="保存" class="icos ico-save" data-bind="click: $parent.save">&nbsp;</span></div>
<i class="kclear"></i>
</li>
</script>
<script id="itemsTmpl" type="text/html">
<li><div><span class="icos ico-del" title="删除工序" data-bind="click:$parent.remove">&nbsp;</span><input type="text" data-bind="value: name,attr:{'itemid':itemid}" disabled="disabled" readonly="readonly" class="id-process"/><span title="修改工序" class="icos ico-ok" data-bind="click: $parent.edit">&nbsp;</span></div>
<i class="kclear"></i>
</li>
</script>