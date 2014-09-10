<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$scrpitS = array(
    'doT.js',
    'k-load-department.js',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);

$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'admin'
    ) ,
    $model['name'],
);
$this->renderPartial('_tabs', array(
    'model' => $model,
    'id' => $id
));
?>


<div class="tab-content">
	<?php echo $model->note; ?>
	<hr />
	<div class="row-fluid department">
		<div class="span3">
			<div class="head clearfix">
				<div class="isw-grid"></div>
				<h1>工人</h1>
			</div>
			<div class="block clearfix">
<?php
$this->renderPartial('_list_worker', array(
    'workerData' => $workerData,
    'id' => $id,
));
?>
			</div>
		</div>
		<div class="span5">
			<div class="head clearfix">
				<div class="isw-grid"></div>
				<h1>工序和价格</h1>
			</div>
			<div class="block clearfix">
<?php
$this->renderPartial('_list_price', array(
    'priceData' => $priceData,
    'id' => $id,
));
?>			
			</div>
		</div>
	</div>
</div>
<?php
Tak::regScript('upproduct', '
	var actionUrl = "' . $this->createUrl('saves', array(
    'id' => $id
)) . '"', CClientScript::POS_END);
?>
<script id="data-worker" type="text/x-dot-template">
<tr id="{{=it.id}}">
<td>
	<input type="text" required="required" value="{{=it.name || ''}}" name="name"/></td>
<td>
	<a title="保存" href="javascript:;" class="icon-ok">&nbsp;</a>&nbsp;
	<a title="取消" href="javascript:;" class="icon-ban-circle">&nbsp;</a>
	</a>
</td>
  </tr>
 </script>  
<script id="view-worker" type="text/x-dot-template">
<tr id="{{=it.id}}">
<td>
		{{=it.name || ''}}
<td>
	<a href="javascript:;" title="修改" class="icon-pencil btn-edit" data-json="{{=it.json}}"></a>&nbsp;
	<a href="{{=it.actionUrl}}" class="icon-remove"></a>
	</a>
</td>
  </tr>
 </script>  
<script id="view-price" type="text/x-dot-template">
<tr id="{{=it.id}}">
<td>
		{{=it.name || ''}}
</td>
<td>
		{{=it.price || ''}}
<td>
	<a href="javascript:;" title="修改" class="icon-pencil btn-edit" data-json="{{=it.json}}"></a>&nbsp;
	<a href="{{=it.actionUrl}}" class="icon-remove"></a>
	</a>
</td>
  </tr>
 </script>  
<script id="data-price" type="text/x-dot-template">
<tr id="{{=it.id}}">
<td>
	<input type="text" required="required" value="{{=it.name || ''}}" name="name"/></td>
	
<td>
	<input type="number" required="required" value="{{=it.price || ''}}" name="price"/></td>
<td>
	<a title="保存" href="javascript:;" class="icon-ok">&nbsp;</a>&nbsp;
	<a title="取消" href="javascript:;" class="icon-ban-circle">&nbsp;</a>
	</a>
</td>
  </tr>
 </script>  