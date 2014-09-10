<?php
  $this->pageTitle = Yii::app()->name . ' - 帮助中心';
  $this->breadcrumbs=array(
    '帮助中心 ',
  );

$this->regCssFile('smart-wizard/styles/smart_wizard.css')
        ->regScriptFile('plugins/smart-wizard/jquery.smartWizard2.js')
        ->regScriptFile('doT.js');

  $model = new Movings;
  $types = TakType::items('Sell-type');
  $warehouses =  Warehouse::getSelect();

$form=$this->beginWidget('CActiveForm', array(
        'id'=>'takllist',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>false,
));
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
                                <?php echo $form->labelEx($model,'typeid');?>
                              </div>
                              <div class="span5">
                                <?php echo $form->dropDownList($model,'typeid',$types,array("required"=>"required"));?>
                              </div>
                            </div>
                            <div class="row-form clearfix">
                               <div class="span2">
                                 <?php echo $form->labelEx($model,'warehouse_id');?>
                               </div>
                               <div class="span5">
                                 <?php echo $form->dropDownList($model,'warehouse_id',$warehouses,array("required"=>"required"));?>
                               </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span2">
                                  <?php echo $form->labelEx($model,'time'); ?>
                                </div>
                                <div class="span5">
                                <?php echo $form->dateField($model,'time',array('required'=>'required','size'=>10,'maxlength'=>10,'value'=>($model->time>0?Tak::timetodate($model->time):'')),array("required"=>"required")); ?>
                                </div>
                            </div>              
                            <div class="row-form clearfix">
                                <div class="span2">
                                  <?php echo $form->labelEx($model,'numbers'); ?>
                                </div>
                                <div class="span5">
                                <?php echo $form->textField($model,'numbers',array('size'=>60,'maxlength'=>100)); ?>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span2">
                                  <?php echo $form->labelEx($model,'enterprise'); ?>
                                </div>
                                <div class="span5">
                                <?php echo $form->textField($model,'enterprise',array('size'=>60,'maxlength'=>100,"required"=>"required")); ?>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span2">
                                  <?php echo $form->labelEx($model,'us_launch'); ?>
                                </div>
                                <div class="span5">
                                <?php echo $form->textField($model,'us_launch',array('size'=>60,'maxlength'=>100)); ?>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span2">
                                  <?php echo $form->labelEx($model,'note'); ?>
                                </div>
                                <div class="span5">
                                <?php echo $form->textArea($model,'note',array('size'=>60,'maxlength'=>255)); ?>
                                </div>
                            </div>
          </div>
            <div id="step-2">
          <div class="toolbar nopadding-toolbar clearfix">
              <h4>产品明细</h4>
          </div>               
      <table cellpadding="0" cellspacing="0" width="100%" class="table" >
          <thead>
            <tr>
            <th width="45">移除</th>
              <th>产品</th>
              <th width="80">规格</th>
              <th width="80">材料</th>
              <th width="80">颜色</th>
              <th width="80">单价</th>
              <th width="80">数量</th>
              <th>备注</th>
            </tr>
          </thead>
          <tbody class="not-mpr" id="product-movings">
            <tr id="data-loading">
              <td colspan="8" class="grid-view-loading">请选择产品型号</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3">
            <span class="">
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'label' => Tk::g(array('Add','Product')),
    'htmlOptions'=>array('id'=>"addproduct")
)); ?>
      </span>              
              </td>
              <td colspan="5"></td>
            </tr>
          </tfoot>
        </table>
          </div>
      </div>
<script id="data-row" type="text/x-dot-template">
  {{~it.tags :v:index}}
<tr id="{{=v.itemid}}">
<td><a href="#" class="data-remove"><span class="icon-remove"></span></a></td>
<td class="info">{{=v.name}}</td>
<td class="info">{{=v.spec}}</td>
<td class="info">{{=v.material}}</td>
<td class="info">{{=v.color}}</td>
<td><input type="number" class="stor-txt" name="Product[{{=v.itemid}}][price]" min="0.1" value="{{=v.price}}"/></td>
<td class="info">
  <input type="number" class="stor-txt" name="Product[{{=v.itemid}}][number]" required="required" value="{{#def.number || ''}}" min="1"/></td>
  <td><input name="Product[{{=v.itemid}}][note]" type="text" class="stor-txt" value="{{=v.note}}"/></td>  
  </tr>
  {{~}}
  </script>      
<?php
$this->endWidget(); 
  $this->regScriptFile('k-load-movings.js?t=1', CClientScript::POS_END);
Tak::regScript('end','
');

?>