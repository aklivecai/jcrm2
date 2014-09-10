<?php
$scrpitS = array(
    '_ak/js/advanced/knockout-3.1.0.js',
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-production-v1.js',
);

$this->regScriptFile($scrpitS, CClientScript::POS_END);

if (1) {
    $jsonp = CJSON::encode($produts);
} else {
    $jsonp = '[{"itemid":"65779604866977210","fromid":"1","cost_id":"65779604866977208","type":"50","name":"20","spec":"20","color":"20","file_path":"","expenses":"10.00","price":"303110.00","numbers":"20.0000","totals":"6061200.00"}]';
}
Tak::regScript('datas', '
	var tags = ' . $jsonp . '
, workshops = ' . CJSON::encode($workshops) . '
	;
', CClientScript::POS_HEAD);

$links = JHtml::link(Tk::g(array(
    'Workshop',
    'Setting'
)) , array(
    'Workshop',
    'dname' => '生产排期'
) , array(
    'class' => 'ibtn',
    'id' => 'btn-workshop'
));
?>
<div id="wrapper">
<ul class="header-tools">
<li>
<?php echo $links ?>
</li>
</ul>
<div class="kclear"></div>
	<div class="mod" id="wap-production">
		<h2>生产排期</h2>
		<div class="modc production-view">
			<form action="" method="post" id="form-submit">
				<table class="zebra" summary="" width="100%">
					<colgroup>
					<col width="15%" />
					<col width="5%" />
					<col width="10%" />
					</colgroup>
					<thead>
						<tr>
							<th>产品</th>
							<th>数量</th>
							<th>车间</th>
							<th>工序</th>
						</tr>
					</thead>
					<tbody data-bind="foreach: lines">
						<tr>
							<td>
								<span data-bind="text: name"></span>
							</td>
							<td><span data-bind="text: numbers"></span></td>
							<td>
							<select data-bind='options: workshops,optionsText: "typename", optionsCaption: "选择车间", value: line' ></select>
						</td>
						<td  class="list-production-process">
							<div data-bind="with:line">
								<input type="hidden" data-bind="value: typeid,attr: {name:$parent.getWName()}"/>
								<ul data-bind="foreach:  {data:process,afterRender:$root.initInput}">
									<li>
										<input type="checkbox" data-bind="attr: {id: $parents[1].getPId(typeid)}" class="check-pro"/>
										<label data-bind="text: typename, attr: {'for':$parents[1].getPId(typeid),}"></label>
										<input type="number" class="days placeholder"  step="0.1" min="0.1" data-bind="attr: {name: $parents[1].getPName(typeid),id: $parents[1].getPName(typeid)}" style="display:none"  placeholder="计划完成天数" disabled="disabled" />
									</li>
								</ul>
							</div>
						</td>
					</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="4">
						<button type="submit" class="ibtn">提交</button>
					</td>
				</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>

<div class="wap-tips not-printf" >
	<span class="tips_icon_help">
	提示:
	</span>
	<div class="tips-mod">
		<ul>
			<li>红色边框为必填选项,不能为空</li>
			<li>选择有工序的时候，天数必须大于0</li>
		</ul>
	</div>
</div>
</div>