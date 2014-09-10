<?php
$scrpitS = array(
    '_ak/js/advanced/knockout-3.1.0.js',
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-production.js',
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
$buttons = array();
$buttons[] = JHtml::link('成本核算', array(
    'view',
    'id' => $id
) , array(
    'class' => 'ibtn'
));
if ($model->status == 3) {
    $buttons[] = JHtml::link('生产进度', array(
        '/Production/View',
        'id' => $id
    ) , array(
        'class' => 'ibtn'
    ));
} else {
}
$buttons[] = '<button type="submit"class="ibtn ibtn-ok"> 计划时间录入 </button>';
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
				<table class="zebra" summary="" width="100%" id="init-production">
					<colgroup>
					<col width="15%" />
					<col width="auto" />
					</colgroup>
					<thead>
						<tr>
							<th>车间</th>
							<th>产品列表</th>
						</tr>
					</thead>
					<tbody data-bind="foreach: lines">
						<tr>
							<td>
							<select data-bind='options: workshopsSelect,optionsText: "name", optionsCaption: "选择车间",optionsValue:"id",value:workshops,valueAllowUnset:true' required="required"></select>
						</td>
						<td><span data-bind="text: name"></span></td>
					</tr>
				</tbody>
			</table>
			<hr />
			<h2>
			车间，产品，工序
			</h2>
			<div  id="init-workshops">
				<table class="zebra" summary="" width="100%">
					<colgroup>
					<col width="10%" />
					</colgroup>
					<thead>
						<tr>
							<th>车间</th>
							<th>产品列表</th>
							<th>工序
								<div class="fr">
									<a id="print-produciotn" href="javascript:;">
									<i class="icon action-print" title="打印">&nbsp;</i>线下计划表
									</a>
								</div></th>
							</tr>
						</thead>
						<tbody data-bind="foreach: lines">
							<!-- ko if: isShow()>0-->
							<tr>
								<td><span data-bind="text:name"></span></td>
								<td>
									<ol class="list-production" data-bind="foreach:products">
										<li>
											<input type="hidden" data-bind="attr: { name:getName()},value:$parent.id">
											<span data-bind="text:name"></span>
										</li>
									</ol>
								</td>
								<td  class="list-production-process">
									<div>
										<ul data-bind="foreach:  {data:process}">
											<li>
												<span data-bind="text: typename"></span>
												<div class="hide show-print L">
													<span>计划完成天数:</span>
													<br />
													<span>计划人:</span>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							<!-- /ko -->
						</tbody>
						<tfoot class="not-printf">
						<tr>
							<td colspan="3">
								<div class="footer-action">
									<a tabindex="-1" class="ibtn ibtn-cancel">关闭窗口</a>
									<?php echo implode("", $buttons); ?>
								</div>
							</td>
						</tr>
						</tfoot>
					</table>
					<!-- ko if: isprintf()-->
					<div id="print-table">
						<table class="list hide show-print" width="100%">
						<colgroup align="center">
							<col width="120px"/>
						</colgroup>
							<thead>
								<tr>
									<th>车间</th>
									<th></th>
								</tr>
							</thead>
							<tbody data-bind="foreach: lines">
								<!-- ko if: isShow()>0-->
								<tr>
									<td><span data-bind="text:name"></span></td>
									<td>
										<table width="100%">										
											<colgroup align="center">
												<col width="80px"/>
											</colgroup>
											<tbody>
												<tr>
													<th class="R">产品列表</th>
													<td>
													<table width="100%">
													<thead>
														<tr>
															<th>品名</th>
															<th>型号</th>
															<th>规格</th>
															<th>颜色</th>
															<th>数量</th>
														</tr>
													</thead>
														<tbody  data-bind="foreach:products">
															<tr>
															<td data-bind="text:obj.type"></td>
															<td data-bind="text:obj.name"></td>
															<td data-bind="text:obj.spec"></td>
															<td data-bind="text:obj.color"></td>
															<td data-bind="text:obj.numbers"></td>
															</tr>
														</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<th class="R">各工序计划完成时间</th>
													<td>
														<div class="list-production-process">
															<ul data-bind="foreach:  {data:process}">
																<li>
																	<strong data-bind="text: typename"></strong>
																	<div class="show-print L">
																		<span>计划完成<i class="hr2">&nbsp;</i>天</span>
																		<br />
																		<span>计划人<i class="hr1">&nbsp;</i></span>
																	</div>
																</li>
															</ul>
														</div>
													</td>
												</tr>
											</tbody>

										</table>


									</td>
								</tr>
								<!-- /ko -->
							</tbody>
						</table>
					</div>
					<!-- /ko -->
				</div>
			</form>
		</div>
	</div>
	<div class="wap-tips not-printf" >
		<span class="tips_icon_help">
		提示:
		</span>
		<div class="tips-mod">
			<ul>
				<li>车间是必选项</li>
			</ul>
		</div>
	</div>
</div>