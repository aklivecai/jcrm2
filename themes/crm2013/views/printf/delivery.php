<?php
$this->breadcrumbs = array(
    Tk::g('Delivery')
);

$scrpitS = array(
    '_ak/js/advanced/knockout-3.1.0.js',
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);

$scrpitS = array(
    'k-load-printf-delivery.js?tt123',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
?>
<div class="row-fluid">
	<div class="head clearfix">
		<div class="isw-grid"></div>
		<h1><?php echo Tk::g('Delivery') ?></h1>
	</div>
	<div class="block-fluid wage-create">
	<form action="<?php echo $this->createUrl('PrintfDelivery')?>" method="post" id="delivery-form" target="_blank">
		<table cellpadding="0" cellspacing="0" width="100%" class="table" id="delivery">
		<!-- col 15-->
			<colgroup align="center">
			<col width="45px" />
			<col width="120px" />
			<col width="auto" span="5"/>
			<col width="120px"/>
			</colgroup>
			<thead>
				<tr>
				<th>序号</th>
				<th>产品型号</th>				
				<th>工单号</th>	
				<th>下单日期</th>				
				<th>皮色</th>
				<th>规格</th>				
				<th>价格</th>
				<th>数量(单位)</th>
				<th>金额</th>
				<th>备注</th>
				<th>包装件数</th>		
				<th></th>
				</tr>
			</thead>
			<tbody data-bind="template: { name: 'list-template', foreach: lines,afterAdd: init}"></tbody>
				<tfoot>
				<tr>
					<td colspan="2"><button class="btn" type="button" tabindex="-1" data-bind="click: add">添加</button></td>
					<td colspan="3">
						<span class="lcol1"><labe for="company">定货单位</labe>:</span>
						<span class="lcol3"><input type="text" name="company" id="company" data-bind="value: company"></span>
					</td>
					<td colspan="1"></td>
					<td colspan="1">合计:
					<strong data-bind="text: totals" class="label label-warning"></strong>
					</td>
					<td colspan="2"><button type="submit" class="btn" data-bind="disable: isSubmit" >打印预览</button></td>
				</tr>
				</tfoot>
			</table>
			</form>
		</div>
	</div>
<label></label>
<script type="text/html" id="list-template">
<tr data-bind="attr: {id:uid}" >
  <td>
  <span data-bind="text:$index()+1"></span> </td>
  <td><input type="text" class="data-select"  data-action="product" data-bind="value: product,attr:{name:getName('product')}" />
  </td>
  <td><input type="text" data-bind="value: serialid,attr:{name:getName('serialid')}"/></td>
  <td>
  <input type="text" class="type-date"  data-bind="value: order_time,attr:{name:getName('order_time')}"/>
  </td>
  <td>
    <input type="text" data-bind="value: color,attr:{name:getName('color')}"/>
  </td>
  <td>
    <input type="text" data-bind="value: standard,attr:{name:getName('standard')}"/>
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
  
  <td>
  	<input type="text" data-bind="attr:{value:note,name:getName('note')}"/>
  </td>
  <td><input type="text" data-bind="attr:{value:numbers, name:getName('numbers')}"/>
  </td>
  <td><a class="btn btn-mini" data-bind="click: $root.remove" href="#" title="取消"><i class="icon-trash"></i></a> </td>
</tr>
</script>
