
<?php
$this->pageTitle = Tk::g(array(
    $model->getTypeName() ,
    'bill'
)) . '-' . $this->cates[$model->typeid];

$company = TestMemeber::model()->findByPk($fid);
$tags = ProductMoving::model()->findAllByAttributes(array(
    'movings_id' => $model->itemid
));

$img = $company->logo;
if ($img) {
    $img = CHtml::image($img);
} else {
    $img = '';
}

if ($model->type == 1) {
    $enterprise = '供应商名字';
    $numbers = '入库单号';
} else {
    $enterprise = '领料部门';
    $numbers = '出库单号';
}

$totals = 0;
?>
<style type="text/css">
	.title span {
		display: block;
		letter-spacing:1.5em;
	}
	.logo{
		position: relative;
		margin-bottom: 28px;
	}
	.logo img{
		width: 100px;
		position: absolute;
		top: 0;			
		left: 10em;
	}
</style>
<div class="content">
<div class="logo">
<h1 class="title">
	<?php echo $company->company ?>	
	<span><?php echo Tk::g(array(
    $model->getTypeName() ,
    'bill'
)); ?>
	</span>
	</h1>
		<?php echo $img; ?>
	</div>
<div>
	<div class="col3">
		<?php echo CHtml::encode($enterprise); ?>:
		<input type="text" value="<?php echo $model->enterprise ?>" >
	</div>
	<div class="col3 txt-center">		
		<?php echo date("Y 年 m 月 d 日", $model->time); ?>
	</div>
	<div class="col3 txt-right">
		<?php echo CHtml::encode($numbers); ?>:
		<input type="text" value="<?php echo $model->numbers ?>" >
	</div>
	<i class="clearfix"></i>
	<table>
			<colgroup align="center">
			<col width="80px"/>
			<col width="auto"/>
			<col width="90px"/>
			<col span="3" width="110">
			<col width="120px"/>
			</colgroup>	
		<thead>
			<tr>
				<th>序号</th>
				<th>材料名称</th>
				<th>单位</th>
				<th>规格</th>
				<th>数量</th>
				<th>单价</th>
				<th>金额</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($tags as $key => $value): ?>
			<tr>
				<td class="txt-center"><?php echo $key + 1; ?></td>
				<td><?php echo $value->iProduct->name ?></td>
				<td class="txt-center"><?php echo $value->iProduct->unit ?></td>
				<td><?php echo $value->iProduct->spec ?></td>
				<td><?php echo Tak::getNums($value->numbers) ?></td>
				<td><?php echo Tak::getNums($value->price) ?></td>
				<td class="txt-bold"><?php echo Tak::getNums($value->total) ?></td>
			</tr>
		<?php
    $totals+= $value->total;
endforeach
?>
		</tbody>
					<tfoot>
			<tr>
				<td colspan="5"></td>
				<td class="txt-right">合计:</td>
				<td><strong><?php echo Tak::getNums($totals) ?></strong></td>
			</tr>
		</tfoot>		
	</table>
	<i class="clearfix"></i>
	<div class="col4">
		记账:
		<input type="text" >
	</div>
	<div class="col4 txt-center">
		仓管:
		<input type="text" >
	</div>
	<div class="col4 txt-center">		
		制票:
		<input type="text" >
	</div>
	<div class="col4 txt-right">
		领料人:
		<input style="width:150px" type="text" value="<?php echo $model->us_launch ?>" >
	</div>
	<i class="clearfix"></i>
	<div class="noprint txt-center footer" >
		<button type="button" onclick="window.print();"><?php echo Tk::g('Print'); ?></button>
		<button type="button" onclick="window.close();"><?php echo Tk::g('Close'); ?></button>
	</div>
</div>
</div>
