<?php
$scrpitS = array(
    'k-load-cost-view.js',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
$produts = $model->getProducts();
$materias = $model->getMaterias();
$stocks = $model->getMateriasStocks();
// Tak::KD($model->attributes);
// Tak::KD($materias);
// Tak::KD($produts);
/*Tak::KD($stocks);*/
?>
<div id="wrapper">
	<div class="mod" id="constac1">
		<h2>物料成本清单核算</h2>
		<div class="modc cost-view">
			<table class="itable">
				<colgroup>
				<col width="8%" />
				<col width="auto" />
				<col width="8%" />
				<col width="auto" />
				</colgroup>
				<tbody>
					<tr>
						<th>名字：</th>
						<td class="txt-left"><?php echo $model->name ?></td>
						<th>日期：</th>
						<td  class="txt-left"><?php echo Tak::timetodate($model->add_time, 6) ?></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<hr/>
			<div class="div-over">
				<table class="itable ilist">
					<caption>
					<div class="fr">
						<a class="icon action-print" title="打印">&nbsp;</a>
						<a class="icon action-fold" title="折叠">&nbsp;</a>
					</div>产品列表</caption>
					<colgroup>
					<col width="auto" span="3" />
					<col width="8%"/>
					<col width="auto" span="4" />
					<col width="45px"/>
					</colgroup>
					<thead>
						<tr>
							<th>品 名</th>
							<th>型 号</th>
							<th>规格</th>
							<th>颜色</th>
							<th>制造管理费</th>
							<th>成本单价</th>
							<th>生产数量</th>
							<th>总成本</th>
							<th class="not-printf">修改</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($produts as $product): ?>
						<tr>
							<td><?php echo $product['type']; ?></td>
							<td><?php echo $product['name']; ?></td>
							<td><?php echo $product['spec']; ?></td>
							<td><?php echo $product['color']; ?></td>
							<td><?php echo Tak::getNums($product['expenses']); ?></td>
							<td><?php echo Tak::getNums($product['price']); ?></td>
							<td><?php echo Tak::getNums($product['numbers']); ?></td>
							<td>
								<span class="text-show prices">
								<?php echo Tak::getNums(($product['numbers'] * $product['price'])) ?>
								</span>
							</td>
							<td class="not-printf">
								<?php echo JHtml::link('修改',array('upProduct','id'=>Tak::setSId($product['itemid'])),array('class'=>'ibtn')); ?>
							</td>
						</tr>
						<?php
endforeach
?>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="8">总成本：<?php echo Tak::getNums($model->totals) ?></td>
						<td class="not-printf"></td>
					</tr>
					</tfoot>
				</table>
			</div>
			<hr/>
			<div class="div-over">
				<table class="itable ilist">
					<caption>
					<div class="fr">
						<a class="icon action-print" title="打印">&nbsp;</a>
						<a class="icon action-fold" title="折叠">&nbsp;</a>
					</div>
					所有材料</caption>
					<colgroup>
					<col width="80px"/>
					</colgroup>
					<tbody>
						<?php foreach ($materias as $typeid => $materia): ?>
						<tr>
							<th>
								<?php echo $typeid == 1 ? '主料' : '辅料'; ?>
							</th>
							<td>
								<?php if (count($materia) == 0): ?>
								没有！
								<?php
    else: ?>
								<div class="div-over">
									<table class="itable ilist">
										<colgroup>
										<col width="80％" />
										<col width="20％" align="right"/>
										</colgroup>
										<thead>
											<tr>
												<th>材料</th>
												<th>用量</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($materia as $value): ?>
											<tr>
												<td class="txt-left">
													<?php echo implode(' , ', array(
                $value['name'],
                $value['spec'],
                $value['color'],
            )); ?>
												</td>
												<td>
													<?php echo Tak::getNums($value['numbers']) , $value['unit'] ?>
												</td>
											</tr>
											<?php
        endforeach
?>
										</tbody>
									</table>
								</div>
								<?php
    endif; ?>
							</td>
						</tr>
						<?php
endforeach
?>
					</tbody>
				</table>
			</div>
			<hr/>
			<div class="div-over">
				<table class="itable ilist">
					<caption>
					<div class="fr">
						<a class="icon action-print" title="打印">&nbsp;</a>
						<a class="icon action-fold" title="折叠">&nbsp;</a>
					</div>
					所有材料库存清单</caption>
					<colgroup>
					<col width="auto"/>
					<col width="15%" span="2" />
					<col width="auto"/>
					</colgroup>
					<thead>
						<tr>
							<th>材料</th>
							<th>合计</th>
							<th>仓库库存</th>
							<th>仓库分布</th>
							<th>需要采购的数量</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($stocks as $key => $value): ?>
						<?php
    $result = '';
    $need = ''; //需要的数量
    if ($value['stocks'] >= 0):
        $need = $value['stocks'] - $value['numbers'];
        if ($need < 0) {
            $result = 'data-lackof';
            $need*= - 1;
        } else {
            $need = '';
            $result = 'data-adequate';
        } else:
            $result = 'not-data';
        endif;
?>
						<tr class="<?php echo $result ?>">
							<td class="txt-left">
								<?php echo implode(' , ', array(
            $value['name'],
            $value['spec'],
            $value['color'],
        )); ?>
							</td>
							<td>
								<i class="text-show">
								<?php echo Tak::getNums($value['numbers']) ?>
								</i>
							</td>
							<?php if ($value['stocks'] == - 1): ?>
							<td colspan="3"></td>
							<?php
        else: ?>
							<td><span class="text-show"><?php echo Tak::getNums($value['stocks']) ?></span></td>
							<td class="txt-left">
								<?php
            if (is_array($value['warehouse'])) {
                foreach ($value['warehouse'] as $key => $value) {
                    echo sprintf('%s:<span class="text-show">%s</span>  ', $key, Tak::getNums($value));
                }
            }
?>
							</td>
							<td align="center">
								<strong class="text-show">
								<?php echo $need ?>
								</strong>
							</td>
							<?php
        endif
?>
						</tr>
						<?php
    endforeach
?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="footer-action not-printf">
			<a tabindex="-1" class="ibtn ibtn-cancel">关闭窗口</a>
		<button class="ibtn-print ibtn" >打印当前页面</button>
		<?php
    if ($model->status == 2) {
        echo JHtml::link('确认生产', array(
            'Production',
            'id' => $id,
        ) , array(
            'class' => 'ibtn'
        ));
    } elseif ($model->status == 3) {
        echo JHtml::link('查看生产进度', array(
            '/Production/View',
            'id' => $id
        ) , array(
            'class' => 'ibtn'
        ));
    }
?>
	</div>
	<div class="wap-tips not-printf" >
		<span class="tips_icon_help">
		提示: ［所有材料库存清单］,数据背景颜色说明
		</span>
		<div class="tips-mod">
			<ul>
				<li class="data-lackof">红色背景，库存不足</li>
				<li class="data-adequate">绿色背景，库存足够</li>
				<li class="not-data">蓝色背景，系统没有材料库存</li>
			</ul>
		</div>
	</div>
</div>