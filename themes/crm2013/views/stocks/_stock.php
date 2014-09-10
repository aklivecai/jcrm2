<?php
$list = Stocks::getTypeStocks($model->primaryKey);
$stocks = $model->getStock();
?>
<table class="table table-striped ">
<thead>
	<tr>
		<th width="80"></th>
		<th>数量</th>
		<th>金额
			(单价:<?php echo Tak::format_price($model->price); ?>)
		</th>
	</tr>
</thead>
	<tbody>
		<tr>
			<th>出库</th>
			<td><?php echo $list[2]; ?></td>
			<td><?php echo Tak::format_price($list[2] * $model->price); ?></td>
		</tr>
		<tr>
			<th>入库</th>
			<td><?php echo $list[1]; ?></td>
			<td><?php echo Tak::format_price($list[1] * $model->price); ?></td>
		</tr>
		<tr>
			<th>当前</th>
			<td><?php echo $stocks; ?></td>
			<td><?php echo Tak::format_price($stocks * $model->price); ?></td>
		</tr>
	</tbody>
</table>
<div class="dr"><span></span></div>