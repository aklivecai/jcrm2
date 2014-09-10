<?php
$this->breadcrumbs = array(
    Tk::g(array(
        'Wage',
        'Admin'
    )) => array(
        'Index'
    ) ,
    Tk::g('工时录入') ,
);

$scrpitS = array(
    '_ak/js/advanced/knockout-3.1.0.js',
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);

$scrpitS = array(
    'k-load-wage-create.js?tt123',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
?>
<div class="row-fluid">
	<div class="head clearfix">
		<div class="isw-grid"></div>
		<h1>工时录入</h1>
	</div>
	<div class="block-fluid wage-create">
	<form action="" method="post" id="wage-form">
		<table cellpadding="0" cellspacing="0" width="100%" class="table" id="wages">
		<!-- col 15-->
			<colgroup align="center">
			<col width="45px" />
			<col width="15%"/>
			<col width="auto" />
			<col width="11%" span="4" />
			<col width="10%"/>	
			<col width="80px"/>	
			<col width="40px" />		
			</colgroup>
			<thead>
				<tr>
				<!--
					<th>序号</th>
					<th>工人姓名</th>
					<th>产品</th>
					<th>工单号</th>
					
					<th>客户</th>
					<th></th>
					<th>数量</th>
					<th>单位</th>
					<th>工序</th>
					<th>工价</th>
					<th>金额</th>
					<th>备注</th>
					<th>完成日期</th>
					<th></th>

				<th>下单日期</th>				
				<th>客户</th>
				<th>型号</th>
				<th>颜色</th>
				<th>规格</th>	
				-->
				<th>序号</th>
				<th>工人</th>
				<th>产品</th>
				<th>工单号</th>	
				<th>工序</th>
				<th>工价</th>
				<th>数量(单位)</th>
				<th>金额</th>
				<th>完成日期</th>
				<th></th>				
				</tr>
			</thead>
			<tbody data-bind="template: { name: 'list-template', foreach: lines,afterAdd: init}"></tbody>
				<tfoot>
				<tr>
					<td colspan="7"><button class="btn" type="button" tabindex="-1" data-bind="click: add">添加</button></td>
					<td>
					<strong data-bind="text: totals"></strong>
					</td>
					<td colspan="2"><button type="submit" class="btn">保存</button></td>
				</tr>
				</tfoot>
			</table>
			</form>
		</div>
	</div>
<label></label>
<script type="text/html" id="list-template">
<tr data-bind="attr: {id:uid}" >
  <td rowspan="2"><span data-bind="text:$index()+1"></span> </td>
  <td><input type="text" class="data-select"  data-action="worker" data-bind="value: worker.name,attr:{name:getName('name')}"/>
    <input type="hidden"  data-bind="value: worker_id,attr:{name:getName('worker_id')}" />
  </td>
  <td><input type="text" class="data-select"  data-action="product" data-bind="value: product,attr:{name:getName('product')}" />
  </td>
  <td><input type="text" data-bind="value: serialid,attr:{name:getName('serialid')}"/></td>
  <td><input type="hidden" data-bind="value: process_id,attr:{name:getName('process_id')}">
    <input type="text" class="data-select"  data-action="dprice" data-bind="value: process,attr:{name:getName('process')}"/>
  </td>
  <td><input class="process-price" type="number" step="any" min="0" data-bind="value:price,attr:{name:getName('price')}" required="required"/>
  </td>
  <td><span class="lcol3">
    <input type="number" step="any" min="0"  data-bind="value: amount,attr:{name:getName('amount')}" required="required"/>
    </span><span class="lcol1">
    <input type="text"  data-bind="value: unit,attr:{name:getName('unit')}"/>
    </span> </td>
  <td><input type="number" step="any" min="0" readonly="readonly"  data-bind="value:sum,attr:{name:getName('sum')}"/>
  </td>
  <td><input type="text" class="type-date" data-bind="attr:{name:getName('complete_time')}" required="required"/>
  </td>
  <td rowspan="2"><a class="btn btn-mini" data-bind="click: $root.remove" href="#" title="取消"><i class="icon-trash"></i></a> </td>
</tr>
<tr class="rows-bottom">
  <td><label>下单日期:
    <input type="text" class="type-date"  data-bind="value: order_time,attr:{name:getName('order_time')}"/>
    </label>
  </td>
  <td><span class="lcol1">客户:</span><span class="lcol3">
    <input type="text" data-bind="value: company,attr:{name:getName('company')}"/>
    </span> </td>
  <td><span class="lcol1">型号:</span><span class="lcol3">
    <input type="text"  data-bind="value: model,attr:{name:getName('model')}"/>
    </span> </td>
  <td><span class="lcol1"> 规格:</span><span class="lcol3">
    <input type="text"  data-bind="value: standard,attr:{name:getName('standard')}"/>
    </span> </td>
  <td><span class="lcol1">颜色:</span><span class="lcol3">
    <input type="text"  data-bind="value: color,attr:{name:getName('color')}"/>
    </span> </td>
  <td><span class="lcol1">备注:</span><span class="lcol3">
    <input type="text" data-bind="value:note,attr:{name:getName('note')}"/>
    </span> </td>
  <td></td>
</tr>
</script>
