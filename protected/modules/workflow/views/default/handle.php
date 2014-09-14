<?php
echo JHtml::tag('h1', sprintf('申请 - 《%s》', $flowInfo->flow_name));
$scrpitS = array(
    '_ak/js/advanced/knockout-latest.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
Tak::regScriptFile('_ak/js/webuploader/webuploader.min.js', 'static', null, CClientScript::POS_END, array(
    'id' => 'webuploader',
    'data-post-url' => Yii::app()->createUrl('TakFile/Upload', array(
        'id' => Tak::setSId(50) ,
    )) ,
));

$this->regCssFile(array(
    'flow/flow-info.css?t=201409',
))->regScriptFile(array(
    'k-load-mvc.js',
    'k-load-flow-info.js?t=201409'
) , CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'e-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'tak-form'
    ) ,
));
?>
<div class="mod">
    <div class="mod-head">流程信息</div>
    <div class="mod-body">
        <table class="itable bordered">
            <colgroup>
                <col width="10%" />
                <col width="auto" />
                <col width="10%" />
                <col width="auto" />
            </colgroup>
            <tbody class="ibody-edit">
                <tr>
                    <th><?php echo $model->getAttributeLabel('title') ?></th>
                    <td align="top"><?php echo JHtml::encode($model->title) ?></td>
                    <th><?php echo $model->getAttributeLabel('status') ?></th>
                    <td align="top"><?php echo JHtml::encode($model->status) ?></td>
                </tr>
                <tr>
                    <th><?php echo $model->getAttributeLabel('user') ?></th>
                    <td align="top"><?php echo $model->userName ?></td>
                    <th><?php echo $model->getAttributeLabel('start_time') ?></th>
                    <td align="top"><?php echo Tak::timetodate($model->start_time, 6) ?></td>
                </tr>
                <tr>
                    <th>
                        <?php echo $model->getAttributeLabel('describe') ?>
                    </th>
                    <td colspan="3">
                        <?php echo JHtml::encode($model->describe) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php $this->renderPartial('_forminfo', array(
    'model' => $model,
    'fowForm' => $fowForm,
    'stepInfo' => $stepInfo,
)); ?>


<?php
$this->renderPartial('_files', array(
    'model' => $model
)); ?>
<div class="flow-tools-wap">
    <div class="flow-tools-body">
    <textarea name="note" placeholder="请填写您的办理理由" class="tools-note" id="note"></textarea>
    <div class="wap-btns">
        <!-- 流程步骤 -->
        <a class=" btn btn-warning target-win" data-width="800" data-height="300" href="<?php echo $this->createUrl('ViewRun', array(
    'id' => $id
)) ?>">流程步骤</a>
        <span id="add-file">添加附件</span>
        <input type="hidden" name="type" value="1" id="itype"/>
        <?php if ($stepInfo->isFirst()): ?>            
        <button class=" btn btn-primary ibtn-submit" data-val="-1" type="submit">重新申请</button>
        <?php
else: ?>
        <button class=" btn btn-primary ibtn-submit" data-val="1" type="submit">同意</button>
        <button class=" btn btn-danger ibtn-submit" data-val="0"  type="submit">退回</button>
    <?php
endif
?>
        
    </div>
    </div>
</div>

<?php $this->endWidget(); ?>