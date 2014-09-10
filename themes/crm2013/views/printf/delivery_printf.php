<?php
$this->pageTitle = $this->pageTitle = Tk::g('Delivery');

$img = $company->logo;
if ($img) {
    $img = CHtml::image($img);
} else {
    $img = '';
}
?>
<style type="text/css">
	body,input{
		font-size: 11pt;
	}
	.title span {
		display: block;
		letter-spacing:1.5em;
	}
	.logo{
		position: relative;
		margin-bottom: 10px;
	}
	.logo img{
		width: 100px;
		position: absolute;
		top: 0;
		left: 10em;
	}
	td,th{
		padding: 0.1em;
		line-height: 1.2em;
	}
	tfoot tr td{
		padding: 5px 2px;
	}
	.txt-center input{
		text-align: center;
	}
	.row{
		margin-bottom: 3px;
	}
	.desc{
		width:50%;margin:0 auto
	}
	.desc h2{
		width: 50%;
		margin: 0 auto;
		border-bottom: 1px solid #000;
		margin-bottom: 2px;
		margin-top: -3px;
	}
	form{
		margin: 0;
		padding: 0 ;
	}
	.col2 input{
		width: 80%;
	}
</style>
<iframe src="about:blank" style="position: absolute;top:-9999;" width="2" height="1" frameborder="0" name="post">
</iframe>
<div class="content">

<form action="<?php echo $this->createUrl('submit'); ?>" method="post" target="post" onsubmit="window.print();return true;">
	<div class="logo">
		<h1 class="title">
		<?php echo $company->company ?>
		</h1>
		<div class="desc">
		<div class="row ">
			<input class="txt-right" style="width:99%;font-size:14pt" type="text"  value="NO:<?php echo $model['no'] ?>"/>
		</div>
		<h2 class="txt-center">
		<?php echo $this->pageTitle = Tk::g('Delivery') ?>
		</h2>		
		<div class="row ">
			<input class="txt-right" style="width:99%;" type="text"  value="送货日期：<?php echo Tak::timetodate(Tak::now()) ?>" />
		</div>
	</div>
		<?php echo $img; ?>
	</div>
	<div class="row">
		<div class="row">
			<input class="txt-center" style="width:99%" type="text"  value="<?php echo $pdata['address'] ?>" name="m[address]" />
		</div>
		<div class="row ">
			<input class="txt-center" style="width:99%" type="text"  value="<?php echo $pdata['tel'] ?>" name="m[tel]" />
		</div>
		<div class="row  col2">
			定货单位：<input type="text" value="<?php echo $model['company'] ?>" />
		</div>
		<div class="row  col2">
			联系电话：<input type="text" value="<?php echo $model['tel'] ?>" />
		</div>
		<i class="clearfix"></i>
		<div class="row  col2">
			联系人：
			<input  type="text" />
		</div>		
		<div class="row  col2">
			顾客地址：<input type="text" value="<?php echo $model['address'] ?>" />
		</div>
		<i class="clearfix"></i>

		<table>
			<colgroup align="center">
			<col width="50px"/>
			<col width="85px" span="2" />
			<col width="125px"/>
			<col width="75px" span="3" />
			<col width="50px"/>
			<col width="80px" span="2" />
			<col width="auto"/>
			<col width="65px"/>
			</colgroup>
			<thead>
				<tr>
					<th>序号</th>
					<th>下单日期</th>
					<th>工单号</th>
					<th>产品型号</th>
					<th>皮色</th>
					<th>规格</th>
					<th>数量</th>
					<th>单位</th>
					<th>单价</th>
					<th>金额</th>
					<th>备注</th>
					<th>包装件数</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tags as $key => $value): ?>
				<tr>
					<td class="txt-center"><?php echo $key + 1; ?></td>
					<td class="txt-center"><?php echo $value['order_time'] ?></td>
					<td><?php echo $value['serialid'] ?></td>
					<td><?php echo $value['product'] ?></td>
					<td><?php echo $value['color'] ?></td>
					<td><?php echo $value['standard'] ?></td>
					<td>
						<?php echo Tak::getNums($value['amount']) ?>
					</td>
					<td class="txt-center">
						<?php echo $value['unit'] ?>
					</td>
					<td><?php echo Tak::getNums($value['price']) ?></td>
					<td class="txt-bold">
						<?php echo Tak::getNums($value['sum']) ?>
					</td>
					<td><?php echo $value['note'] ?></td>
					<td>
						<?php echo $value['numbers'] ?>
					</td>
				</tr>
				<?php
endforeach
?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="6">合计人民币￥：<input style="width:70%" type="text"  value="<?php echo $model['totalA'] ?>" />
			</td>
			<td colspan="6">小写合计￥：<input style="width:75%" type="text"  value="<?php echo $model['totals'] ?>" /></td>
		</tr>
		<tr>
			<td colspan="12">
				<input style="width:100%" type="text" value="<?php echo $pdata[p1] ?>" name="m[p1]" />
			</td>
		</tr>
		<tr>
			<td colspan="12">
				<input style="width:100%" type="text" value="<?php echo $pdata[p2] ?>" name="m[p2]" />
			</td>
		</tr>
		<tr>
		<td colspan="12">
			<h2>付款方式：</h2>
		</td>
		</tr>
		<tr>
			<td colspan="6">收货单位签收：<input type="text" /></td>
			<td colspan="6">共计： 
			<input type="text" style="width:10%;text-align: center" />
			件
			<input type="text" style="width:10%;text-align: center" />
			方
			</td>
		</tr>
		<tr>
			<td colspan="6">货运/物流签收 ：<input type="text" /></td>
			<td colspan="6">送货： <input type="text" value="<?php echo $pdata[p3] ?>" name="m[p3]" /></td>
		</tr>
		<tr>
			<td colspan="6">货运部电话：<input type="text" value="<?php echo $pdata[p4] ?>" name="m[p4]" /></td>
			<td colspan="6">审核： <input type="text" value="<?php echo $pdata[p5] ?>" name="m[p5]" /></td>
		</tr>
		<tr>
			<td colspan="6">经办人：<input type="text" value="<?php echo $pdata[p6] ?>" name="m[p6]" /></td>
			<td colspan="6">制单： <input type="text" value="孙玉婷"  value="<?php echo $pdata[p7] ?>" name="m[p7]" /></td>
		</tr>
		</tfoot>
	</table>
	<i class="clearfix"></i>
	<div>
		<p>
		附约：
		</p>
		<ol>
			<li>本送货单一经收货方或其代理人，指定的运输部门签字或盖章后发货，即等于买卖合同成立生效；</li>
			<li>货到收货方应立即报检验收，如有异议十天内提出，超出验收期限未提出异议，视为供货交付的产品质量合格；</li>
			<li>如代办托运的费用由收货方负责，货品的的所有权交付托运之日起转移给买方所有，其损坏、灭失之风险也随之转转移；</li>
			<li>如发生纠纷，双方友好协商，协商解决未果的，由供货方所在地人民法院管辖。</li>										</li>
		</ol>
	</div>
	<i class="clearfix"></i>
	<div class="noprint txt-center footer" >
		<button type="submit"><?php echo Tk::g('Print'); ?></button>
		<button type="button" onclick="window.close();"><?php echo Tk::g('Close'); ?></button>
	</div>
</div>
</form>
</div>
