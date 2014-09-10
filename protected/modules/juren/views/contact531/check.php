<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */
?>
<table>
<?php if ($clientele == null): ?>
<caption>是否确认操作</caption>
    <thead>
        <tr>
            <th><?php echo $model->clientele_name ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
        <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
             <a href="<?php echo $this->createUrl('toClientele', array(
        'id' => $model->primaryKey
    )) ?>" class="ibtn">确认</a>
            <button class="btn-close">关闭</button>
            </td>
        </tr>
    </tfoot>
<?php
else:
?>
    <thead>
        <tr>
            <th colspan="2"><strong> <?php echo $model->clientele_name ?> 已经存在</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>录入者</th>
            <td>
                <?php echo $clientele->iManage->user_nicename ?>
            </td>
        </tr>
        <tr>
            <th>录入时间</th>
            <td>
                <?php echo Tak::timetodate($clientele->add_time, 6) ?>
            </td>
        </tr>        
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <button class="btn-close">关闭</button>
            </td>
        </tr>
    </tfoot>
<?php
endif
?>
</table>

