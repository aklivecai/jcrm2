<?php
$this->regCssFile('smart-wizard/styles/smart_wizard.css')->regScriptFile('plugins/smart-wizard/jquery.smartWizard2.js');

Tak::regScriptFile(array(
    'knockout.js',
    'knockout.tak.js',
) , 'static', '_ak/js/advanced');

$this->regScriptFile('k-load-movings.js?t=201409', CClientScript::POS_END);

$action = $model->isNewRecord ? 'Entering' : 'Update';
$warehouses = Warehouse::toSelects(false, Permission::iSWarehouses());

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'takllist',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
));
echo JHtml::hiddenField('type', Tak::setCryptKey($this->type));
?>
<!-- Smart Wizard -->
<div id="wizard" class="swMain">
  <ul>
    <li><a href="#step-1">
      <span class="stepNumber">第</span>
      <span class="stepDesc">
      一步<br />
      <small>单据信息</small>
      </span>
    </a></li>
    <li><a href="#step-2">
      <span class="stepNumber">第</span>
      <span class="stepDesc">
      二步<br />
      <small>产品明细</small>
      </span>
    </a></li>
    <li><div class="steps-finish">
      完成
    </li>
  </ul>
  <div id="step-1" class="block-fluid">
    <div class="toolbar nopadding-toolbar clearfix">
      <h4>单据信息</h4>
    </div>
    <div class="row-form clearfix">
      <div class="span2">
        <?php echo $form->labelEx($model, 'typeid'); ?>
      </div>
      <div class="span5">
        <?php echo $form->dropDownList($model, 'typeid', $this->cates, array(
    "required" => "required"
)); ?>
      </div>
    </div>
    <div class="row-form clearfix">
      <div class="span2">
        <?php echo $form->labelEx($model, 'warehouse_id'); ?>
      </div>
      <div class="span5">
        <?php echo $form->dropDownList($model, 'warehouse_id', $warehouses, array(
    "required" => "required"
)); ?>
      </div>
    </div>
    <div class="row-form clearfix">
      <div class="span2">
        <?php echo $form->labelEx($model, 'time'); ?>
      </div>
      <div class="span5">
        <?php echo $form->dateField($model, 'time', array(
    'required' => 'required',
    'size' => 10,
    'maxlength' => 10,
    'value' => ($model->time > 0 ? Tak::timetodate($model->time) : '')
) , array(
    "required" => "required"
)); ?>
      </div>
    </div>
    <div class="row-form clearfix">
      <div class="span2">
        <?php echo $form->labelEx($model, 'numbers'); ?>
      </div>
      <div class="span5">
        <?php echo $form->textField($model, 'numbers', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
      </div>
    </div>
    <div class="row-form clearfix">
      <div class="span2">

        <label for="Movings_enterprise" class="required">
          <?php echo $model->getAttributeLabel('enterprise'); ?>
        <span class="required">*</span></label>
      </div>
      <div class="span5">
        <?php echo $form->textField($model, 'enterprise', array(
    'size' => 60,
    'maxlength' => 100,
    "required" => "required"
)); ?>
      </div>
    </div>
    <div class="row-form clearfix">
      <div class="span2">
        <?php echo $form->labelEx($model, 'us_launch'); ?>
      </div>
      <div class="span5">
        <?php echo $form->textField($model, 'us_launch', array(
    'size' => 60,
    'maxlength' => 100
)); ?>
      </div>
    </div>
    <div class="row-form clearfix">
      <div class="span2">
        <?php echo $form->labelEx($model, 'note'); ?>
      </div>
      <div class="span5">
        <?php echo $form->textArea($model, 'note', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
      </div>
    </div>
  </div>
  <div id="step-2">
    <div class="toolbar nopadding-toolbar clearfix">
      <h4>产品明细</h4>
    </div>
    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="table-product">
      <colgroup align="center">
      <col width="25px" align="center" />
      <col width="auto" span="5"/>
      <col span="2" width="100px"/>
      <col width="120px" />
      <col width="65"/>
      </colgroup>
      <caption>
      </caption>
      <thead>
        <tr>
          <th>ID</th>
          <th>产品</th>
          <th>规格</th>
          <th>材料</th>
          <th>颜色</th>
          <th>备注</th>
          <th>单价</th>
          <th>数量</th>
          <th>合计</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody data-bind="foreach: list " id="product-movings">
        <tr data-bind="attr:{id:eid}">
          <td data-bind="text: $index()+1"></td>
          <td data-bind="text: obj.name"></td>
          <td data-bind="text: obj.spec"></td>
          <td data-bind="text: obj.material"></td>
          <td data-bind="text: obj.color"></td>
          <td><input  data-bind='value: note,attr:{name:getName("note")}' class="stor-txt" type="text"/></td>
          <td>
            <input data-bind='value: price,attr:{name:getName("price")}' required  set="any" type="number" min="0" class="stor-txt"/>
          </td>
          <td><input data-bind="value: number ,attr:{name:getName('numbers')}"  required set="any" type="number" min="0" class="stor-txt"/></td>
          <td>￥<strong data-bind="text: totals" class="text-show"></strong></td>
          <td class="buttons">
            <a class="btn btn-mini" data-bind="click: $root.remove" href="#" title="取消"><i class="icon-trash"></i></a>
          </td>
        </tr>

      </tbody>
      <tfoot>
      <tr>
        <td colspan="8">
          <span>
          <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'label' => '选择库存产品',
    'htmlOptions' => array(
        'id' => "addproduct"
    )
)); ?>
          </span>
          <?php if ($this->type == 1): ?>
          <span>
          <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label' => '新建产品录入',
        'htmlOptions' => array(
            'id' => "create-product"
        )
    )); ?>
          </span>
        <?php
endif ?>
        </td>
        <td colspan="2">￥<strong data-bind="text: totals" class="text-show"></strong></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>

<?php
$this->endWidget();
Tak::regScript('end', '');
?>
<script id="itemsTmpl" type="text/html"></script>