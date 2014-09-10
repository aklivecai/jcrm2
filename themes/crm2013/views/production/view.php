<?php
$scrpitS = array(
    'k-load-production-view.js?t=1&',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);
$buttons = array();
if ($model->isOver()) {
    $actionPP = '';
} else {
    $buttons[] = JHtml::link('产品选择车间', array(
        'cost/production',
        'id' => $id
    ) , array(
        'class' => 'ibtn'
    ));
    $buttons[] = JHtml::link('修改计划时间', array(
        'production/process',
        'id' => $id
    ) , array(
        'class' => 'ibtn'
    ));
    Tak::regScript('upproduct', "
var viewUrl = '" . $this->createUrl('view', array(
        'id' => $id
    )) . "',postUrl = '" . $this->createUrl('ProductionProgresss', array(
        'id' => $id
    )) . "';
", CClientScript::POS_END);
    
    $actionPP = "
<div class='not-printf'>
    <a class='ibtn btn-add'>完成进度</a>
    <div class='wap-progresss-action'>
        <div>
            <label class=''>今天完成进度:
              <textarea class='progress' rows='8' cols='80' maxlength='250' size='250'></textarea>
            </label>
            <button class='ibtn ibtn-cancel'>取消</button>
            <button class='ibtn ibtn-save'>保存</button>
            <hr />
        </div>
        <button class='ibtn ibtn-ok'>已完成</button>
    </div>
</div>
</div>";
}
$buttons[] = '<button class="ibtn-print ibtn">打印当前页面</button>';
?>
<div id="wrapper">
<div class="mod" id="">
    <h2>生产进度详情</h2>
    <div class="modc production-view">
        <table class="itable iedit">
            <colgroup>
            <col width="8%" />
            <col width="auto" />
            <col width="8%" />
            <col width="auto" />
            </colgroup>
            <tbody>
                <tr>
                    <th>名字：</th>
                    <td><?php echo $model->name ?></td>
                    <th>日期：</th>
                    <td ><?php echo Tak::timetodate($model->add_time, 6) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>客户：</th>
                    <td><?php echo $model->company ?></td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>
        <hr/>
        <table class="itable ilist" summary="" width="100%">
            <colgroup>
            <col width="15%" />
            <col width="5%" />
            <col width="auto" />
            </colgroup>
            <thead>
                <tr>
                    <th>生产产品列表</th>
                    <th>车间</th>
                    <th>工序</th>
                </tr>
            </thead>
            <tbody class="list-production">
                <?php foreach ($data as $value): ?>
                <tr class="<?php echo $value['status'] ? 'sover' : ''; ?>">
                    <td>
                        <?php
    $ul = array(
        '<ol>'
    );
    foreach ($value['product'] as $iproduct) {
        $ul[] = sprintf("<li>%s</li>", $iproduct['name']);
    }
    $ul[] = '</ol>';
    echo implode('', $ul);
?>
                    </td>
                    <td class="txt-right">
                        <?php echo $value['name'] ?>
                    </td>
                    <td>
                        <table class="zebra" width="100%">
                            <?php
    $th = array();
    $td = array();
    foreach ($value['process'] as $process) {
        $th[] = sprintf("<th>%s </th>", $process['name']);
        $_status = $process['status'] == 1;
        $listPro = array();
        if ($_status) {
            //
            $day = 0;
            $strTart = '';
            if (count($process['list']) == 1) {
                $day = 1;
            } else {
                $t1 = Tak::getDayStart($process['list'][0]['add_time']);
                
                $n = count($process['list']) - 1;
                $t2 = Tak::getDayStart($process['list'][$n]['add_time']);
                if ($t1 == $t2) {
                    $day = 1;
                } else {
                    $day = round(($t2 - $t1) / 3600 / 24) + 1;
                    $strTart = sprintf('<br />[%s 到 %s] ', Tak::timetodate($t1) , Tak::timetodate($t2));
                }
            }
            $listPro[] = sprintf('<strong>已完成</strong> <br />实际天数:%s天 %s', $day, $strTart);
            $_action = '';
        } else {
            if (count($process['list']) == 0) {
                $listPro[] = '<strong>未开始</strong>';
            } else {
                $listPro[] = '<strong>进度</strong>';
            }
            $_action = $actionPP;
        }
        $listPro[] = '<ul>';
        foreach ($process['list'] as $ke1 => $pps) {
            $listPro[] = sprintf("<li>&nbsp;&nbsp; [%s]: %s</li>", Tak::timetodate($pps['add_time'], 4) , $pps['progress']);
        }
        $listPro[] = '</ul>';
        $__data = array(
            ':days' => $process['value'],
            ':planner' => $process['planner'],
            ':itemid' => $process['itemid'],
            ':list' => implode('', $listPro) ,
            ':action' => $_action,
            ':status' => $_status ? 'sover' : '',
        );
        // 实际完成
        $td[] = strtr("<td class=':status'>计划人: :planner<br />预期时间: :days天<hr /><div class='list-progresss' id=':itemid'> :list :action</td>", $__data);
    }
?>
                            <thead>
                                <tr>
                                    <?php echo implode('', $th); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php echo implode('', $td); ?>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
endforeach
?>
            </tbody>
        </table>
    </div>
</div>
<div class="footer-action not-printf">
<a tabindex="-1" class="ibtn ibtn-cancel" onclick="window.close()">关闭窗口</a>
<?php
$buttons[] = JHtml::link('', Yii::app()->createUrl($this->route, array(
    '
    id' => $id
)) , array(
    'id' => 'tak-location'
));
echo implode("", $buttons);
?>
</div>
</div>