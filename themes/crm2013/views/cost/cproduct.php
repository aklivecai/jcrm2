<?php Tak::regScriptFile($this->createUrl('/iak/index') , '', null, CClientScript::POS_END);
$scrpitS = array(
    '_ak/js/advanced/linq.min.js',
    '_ak/js/advanced/knockout-latest.js',
    '_ak/js/lib.js',
    '_ak/js/plupload/plupload.full.min.js',
    '_ak/js/plupload/i18n/zh_CN.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-cost-base.js?202',
    'k-load-cost-cproduct.js?206',
);
if ($model->isNewRecord&&!$iscopy) {
    $json = '{}';
} else {
    $json = $this->getJson();
}
$this->regScriptFile($scrpitS, CClientScript::POS_END);
$tmps = array('var tags = []');
$tmps[] =sprintf(' itemid = "%s"',$id);
$tmps[] =sprintf(' iscopy = %s',$iscopy?'true':'false');
$tmps[] =sprintf(' product = %s',$json);
$tmps[] =sprintf(' uploadUrl = "%s"',$this->createUrl('/it/Upload'));
Tak::regScript('footer', implode(",", $tmps).';', CClientScript::POS_HEAD); ?>
<div id="wrapper">

    <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'form-const',
    'enableAjaxValidation' => false,
    'action'=>array(($model->isNewRecord?'CreateProduct':'UpProduct'),'id'=>$id),
)); ?>
    <div class="mod" id="cost-product">
        <h2>
            <?php echo $model->isNewRecord ? '新建' : '修改'; ?> 产品核算

        </h2>
        <div class="modc">
            <div>
                <table class="itable table-product">
                    <caption>
                        <div class="product-info">
                            <label>品 名：
                                <input type="text" class="input-bborder" data-bind="value:type,attr:{name:getName('type')}" required/>
                            </label>
                            <label>型 号：
                                <input type="text" class="input-bborder" data-bind="value:name,attr:{name:getName('name')}" required/>
                            </label>
                            <label>规格：
                                <input type="text" class="input-bborder" data-bind="value:spec,attr:{name:getName('spec')}" required/>
                            </label>
                            <label>颜色：
                                <input type="text" class="input-bborder" data-bind="value:color,attr:{name:getName('color')}" required/>
                            </label>
                            <label>制造管理费：
                                <input type="number" class="expenses" value="0" data-bind="value: expenses,attr:{name:getName('expenses')}" step="any" />
                            </label>
                            <label <?php if (!$iscost) echo 'class="hide"' ?>>生产数量：
                                <input type="number" class="input-bborder" value="1" data-bind="value:numbers,attr:{name:getName('numbers')}" required step="any" />
                            </label>
                            <label class="fbold">
                                成本单价:￥
                                <input type="text" readonly="readonly" value="0" class="text-show prices" data-bind="value:price,attr:{name:getName('price')}" tabIndex="-1" />
                            </label>
                            <label class="fbold <?php if (!$iscost) echo ' hide' ?>">
                                总成本:￥
                                <input type="text" readonly="readonly" value="0" class="text-show" data-bind="value:totals,attr:{name:getName('totals')}" tabIndex="-1" />
                            </label>
                        </div>
                    </caption>
                    <colgroup align="center">
                        <col width="80px" />
                    </colgroup>
                    <tbody>
                        <tr data-bind="template:{name: 'materia-template', data:mainMaterias }"></tr>
                        <tr data-bind="template:{name: 'materia-template', data:subMaterias }"></tr>
                        <tr data-bind="template:{name: 'process-template', data:process ,afterRender:initUpload}"></tr>
                    </tbody>
                </table>
                <hr/>
            </div>
        </div>
        <div class="footer-action">
            <a tabindex="-1" class="ibtn ibtn-cancel">关闭窗口</a>
            <?php if ($iscost): ?>
            <a tabindex="-1" href="<?php echo $this->createUrl('view', array(
        'id' => Tak::setSId($model->cost_id)
    )) ?>" class="ibtn">返回核算明细</a>
            <?php
elseif (!$model->isNewRecord): ?>
            <a tabindex="-1" href="<?php echo $this->createUrl('CreateProduct') ?>" class="ibtn">继续添加</a>

            <a tabindex="-1" href="<?php echo $this->createUrl('Copy',array('id'=>$id)) ?>" class="ibtn">复制核算</a>
            <?php
endif
?>
            <button class="ibtn ibtn-ok" type="submit">保存</button>
        </div>
        <div class="wap-tips">
            <span class="tips_icon_help">
                提示: 核算说明
            </span>
            <div class="tips-mod">
                <ul>
                    <li>管理制造费:如本产品需要加人员管理,设备折旧或其他费用可统计在
                        <span class="text-show">制造管理费</span>中;</li>
                    <li>输入中,涉及数量,单价,为必填选项,不能为空或者0;</li>
                    <li>红色边框为必填选项,不能为空</li>
                    <li>上传文件支持: 格式为jpg,gif,png,jpeg,文件大小不要超过5M</li>
                </ul>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

<?php $this->renderPartial('_mvc'); ?>
