<?php
Tak::regScriptFile('_ak/js/lib.js', 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-production-days.js',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);

$buttons = array();
$buttons[] = $links = JHtml::link('产品选择车间', array(
    'cost/production',
    'id' => $id
) , array(
    'class' => 'ibtn'
));
if ($model->status > 1) {
    $buttons[] = $links = JHtml::link('生产进度', array(
        '/Production/View',
        'id' => $id
    ) , array(
        'class' => 'ibtn'
    ));
} else {
}
$buttons[] = '<button type="submit"class="ibtn ibtn-ok"> 提交 </button>';
?>
<div id="wrapper">
	<div class="mod" id="wap-production">
		<div class="modc production-view">
			<h2>
			生产排期(计划时间录入)
			</h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'form-submit',
    'enableAjaxValidation' => false,
)); ?>

			<table class="zebra" summary="" width="100%"  id="init-workshops">
				<colgroup>
				<col width="10%" />
				<col width="15%" />
				</colgroup>
				<thead>
					<tr>
						<th>车间</th>
						<th>产品列表</th>
						<th>各工序计划完成时间</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data as $works_id => $work): ?>
					<tr>
						<td><span><?php echo $work['name'] ?></span></td>
						<td>
							<?php
    $ul = array(
        '<ol class="list-production">'
    );
    foreach ($work['product'] as $iproduct) {
        $ul[] = sprintf("<li>%s</li>", $iproduct['name']);
    }
    $ul[] = '</ol>';
    echo implode('', $ul);
?>
						</td>
						<td  class="list-production-process-days">
							<div>
								<ul>
<?php if (isset($work['process'])&&is_array($work['process'])) :?>
		<?php foreach ($work['process'] as $process_id => $process): ?>
				<?php
        $_id = sprintf("%s-%s", $works_id, $process['typename']);
        $islive = isset($iprocess[$_id]);
        $pars = array(
            'class' => "days",
            "step" => "0.1",
            " min" => "0.1",
        );
        $pen =  array(
            'class' => "",
        );
        if ($islive) {
            $v = $iprocess[$_id]['days'];
            $vplanner = $iprocess[$_id]['planner'];
            $pen["required"] = $pars["required"] = "required";
            $style = '';
        } else {
            $vplanner = $v = '';
            $style = "hide";
            $pen["disabled"] = $pars["disabled"] = "disabled";
        }
        $html = array(
            '<li>'
        );
        $html[] = JHtml::checkBox('', $islive, array(
            'id' => $works_id . $process_id,
            'class' => "check-pro"
        ));
        $html[] = JHtml::label($process['typename'], $works_id . $process_id, array(
            'class' => "check-pro"
        ));
        
        $html[] = sprintf('<div class="d-content %s">', $style);
        $html[] = '计划时间';
        $html[] = JHtml::numberField(sprintf('M[%s][%s][days]', $works_id, $process_id) , $v, $pars);
        $html[] = '天<br />计划人';
        $html[] = JHtml::textField(sprintf('M[%s][%s][planner]', $works_id, $process_id) , $vplanner, $pen);
        $html[] = '</div>';
        
        $html[] = '</li>';
        echo implode("", $html);
?>
<?php
    endforeach
?>

<?php
    endif
?>
									</ul>
								</div>
							</td>
						</tr>
						<?php
endforeach
?>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="3">
							<div class="footer-action">
						<a tabindex="-1" class="ibtn ibtn-cancel" onclick="window.close()">关闭窗口</a>
						<?php echo implode("", $buttons) ?>
						</div>
						</td>
					</tr>
					</tfoot>
				</table>
			<?php $this->endWidget(); ?>
		</div>
	</div>
	<div class="wap-tips not-printf" >
		<span class="tips_icon_help">
		提示:
		</span>
		<div class="tips-mod">
			<ul>
				<li>红色边框为必填选项,不能为空</li>
				<li>选择有工序的时候，计划完成天数必须大于0</li>
			</ul>
		</div>
	</div>
</div>