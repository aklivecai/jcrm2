<?php
/**
 *
 * 查看流程详细步骤
 * @date    2014-09-13 15:13:03
 * @version $Id$
 */
echo JHtml::tag('h1', '查看流程详细步骤');
$list = $model->getRunPics();
?><table class="itable bordered">
    <colgroup align="center">
        <col width="45px"  />
        <col width="auto"  />
        <col width="80px"  />
        <col width="auto"  />
        <col width="80px"  />
        <col width="auto"  />
        <col width="85px"  />
    </colgroup>

    <thead>
        <tr>
            <th></th>
            <th>步骤名称</th>
            <th>状态</th>
            <th>经办人/开始时间</th>
            <th>持续时间</th>
            <th>办理理由</th>
            <th>超期时间</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $key => $value): ?>
        <tr>
            <th>
                <?php echo $key + 1 ?>
            </th>
            <td>
                <?php echo $value->step_name ?></td>
                <td>
                <?php if ($value->step_no == 1 && $key > 0): ?>
                    重新申请<br />
                <?php
    endif
?>
                <?php echo $value->statuName ?>
                </td>
                <td>
                <?php echo FlowRun::getUsername($value->step_user) ?><br />
                <?php echo Tak::timetodate($value->start_time, 6) ?>
                </td>
            <td><?php echo $value->getRunTime($key) ?></td>
              <td><?php echo $value->remark ?></td>
            <td><?php if ($value->timeout > 0 && $value->handel_time == 0) {

                printf('<strong class="color_orange">%s</strong><br />%s',Tak::timediff($value->timeout),Tak::timetodate($value->timeout));
                    }
?></td>
        </tr>
<?php
endforeach ?>
<?php if ($model->run_state == 1): ?>
    <tr>
    <th><?php echo $key + 2 ?></th>
    <td><strong class="color_green">结束</strong></td>
    <td colspan="5">
        处理时长:
        <strong class="color_blue">
            <?php echo Tak::timediff($model->start_time, $model->end_time) ?>
        </strong>
    </td>
    </tr>
<?php
endif ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7"></td>
        </tr>
    </tfoot>
</table>
