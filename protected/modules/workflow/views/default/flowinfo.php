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
?><div class="mod">
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
                    <th>
                        <?php echo $form->labelEx($model, 'flow_name'); ?></th>
                    <td align="top">
                        <?php echo $form->textField($model, 'flow_name',array('requiredx'=>'required')); ?>
                        <?php echo $form->error($model, 'flow_name'); ?>
                    </td>

                    <th>
                        <?php echo $form->labelEx($model, 'describe'); ?></th>
                    <td>
                        <?php echo $form->textArea($model, 'describe',array('requiredx'=>'required')); ?>
                        <?php echo $form->error($model, 'describe'); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $this->renderPartial('_forminfo', array(
    'model' => $model,
    'fowForm' => $fowForm,
    'step' => $step,
)); ?>

<?php $this->renderPartial('_files', array(
    'model' => $model,
    'fowForm' => $fowForm,
    'step' => $step,
)); ?>
<div class="flow-tools-wap">
    <div class="flow-tools-body">
    <textarea name="" placeholder="请填写您的办理理由" class="tools-note"></textarea>
    <div>
        <span id="add-file">添加附件</span>
        <button class=" btn" type="submit">提交</button>
    </div>
    </div>
</div>

<?php $this->endWidget(); ?>