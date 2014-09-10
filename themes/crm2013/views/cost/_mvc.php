<a href="about:blank" id="tak-load"></a>
<script type="text/html" id="materia-template">
<th>
	<span data-bind="text: typeName"></span>
	<br />
	<button type="button" class="ibtn" data-bind="click: add">添项</button>
</th>
<td>
	<div class="div-over">
		<table class="itable ilist">
			<colgroup align="center">
			<col width="160px" />
			<col span="6" width="auto"/>
			<col width="110px"/>
			<col width="45px"/>
			</colgroup>
			<thead>
				<tr>
					<th>材料</th>
					<th>规格</th>
					<th>单价</th>
					<th >用量</th>
					<th width="65">单位</th>
					<th>颜色</th>
					<th>备注说明</th>
					<th>合计</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody data-bind='foreach: {data:lines, afterRender: initElement}'>
				<tr data-bind="attr:{id:getId('id')}">
					<td>
						<div class="tak-combobox">
							<input type="hidden" class="product-itemid" data-bind="value: product_id,attr:{name:getName('product_id')}">
							<input type="text" class="product-id" data-bind='value: name,css: { error: name.hasError },attr:{name:getName("name"),title:name.validationMessage}' required/>
							<span class="iselect">&nbsp;</span>
							<div class="dropdownlist tips-loading">
							</div>
						</div>
					</td>

					<td><input type="text" class="spec" data-bind="value: spec,attr:{name:getName('spec')}"></td>
					<td><input class="price" type="number" step="any" min="0" data-bind="value: price,attr:{name:getName('price')}" required/></td>					
					<td><input class="number" type="number" step="any" min="0" data-bind="value: numbers,attr:{name:getName('numbers')}" required/></td>
					<td><input type="text" class="unit" data-bind="value: unit,attr:{name:getName('unit')}"/></td>
					<td><input type="text" class="color" data-bind="value: color,attr:{name:getName('color')}"></td>

					<td><input type="text" class="note" data-bind="value: note,attr:{name:getName('note')}"/></td>
					<td>￥<input type="text" class="text-show total" readonly="readonly" value="0" data-bind="value: totals,attr:{name:getName('totals')}"  tabIndex="-1"/></td>
					<td><button type="button" class="ibtn btn-del" data-bind="click:$parent.remove">删除</button></td>
				</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="9">
					<div class="txt-left">
						<span data-bind="text: typeName"></span>合计: ￥
						<input type="text" readonly="readonly" value="0" class="text-show" data-bind="value: totals"  tabIndex="-1"/>
					</div>
				</td>
			</tr>
			</tfoot>
		</table>
	</div>
</td>
</script>
<script type="text/html" id="process-template">
<th>
	工序
	<br />
	<button type="button" class="ibtn" data-bind="click: add">添项</button>
</th>
<td>
	<div class="div-over wap-process fl">
		<table class="itable ilist">
			<colgroup align="center">
			<col span="3" width="auto"/>
			<col width="45px"/>
			</colgroup>
			<thead>
				<tr>
					<th>工序设定</th>
					<th>工价</th>
					<th>备注说明</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody data-bind='foreach: {data:lines}' data="afterAdd: init">
				<tr>
					<td>
						<input type="text" data-bind="value: name,css: { error: name.hasError },attr:{name:getName('name'),title:name.validationMessage}" required/>
					</td>
					<td><input class="price" type="number" step="any" min="0" data-bind="value: price,attr:{name:getName('price')}" required/></td>
					<td><input  type="text" data-bind="value: note,attr:{name:getName('note')}"/></td>
					<td><button type="button" class="ibtn btn-del" data-bind="click:$parent.remove">删除</button></td>
				</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="4">
					<div class="txt-left">
						工序合计: ￥
						<input type="text" readonly="readonly" value="0" class="text-show" data-bind='value: totals'  tabIndex="-1"/>
					</div>
				</td>
			</tr>
			</tfoot>
		</table>
	</div>
  <div class="wap-file" data-bind="with:$parent">
  <input type="hidden"  data-bind="value:file_path, attr:{name:getName('file_path')}"/>
    <strong>产品图片</strong>
         <div data-bind="visible: isfile">
			<div data-bind="attr:{id:getId('container')}">
				<div class="filelist"></div>
			    <a data-bind="attr:{id:getId('pickfiles')}" class="ibtn" href="javascript:;">上传文件</a>
			</div>
        </div>
         <div data-bind="ifnot: isfile" class="img-preview">
    			<a data-bind="attr:{href:file_path}" target="_blank">
    			<img data-bind="attr:{src:file_path}"/>
    		</a>
    		<button type="button" class="ibtn btn-del" data-bind="click:removePic">删除</button>
        </div>
  </div>
</td>
</script>
