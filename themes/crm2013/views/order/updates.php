<?php
$itemid = $model->itemid;
$fromid = $model->fromid;

$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    $itemid,
);
$orderInfo = $model->getOrderInfo();
$listStatus = $model->getListStatus();
$orderStatus = OrderType::items('order-status');
$actionStatus = TakType::items('order-flow', true);

$pay_type = OrderConfig::getAlipay($orderInfo->pay_type, $fromid);

$optionsStatus = array();
foreach ($orderStatus as $key => $value) {
    if ($key > 12) {
        $temp = array(
            'label' => $value
        );
        if (($key > $model->status && $model->status > 10 && !($key == 200 || $key == 101)) || ($model->status == 1 && ($key == 200 || $key == 101))) {
            $temp['url'] = $this->createUrl('status', array(
                'id' => $id,
                'status' => $key
            ));
        }
        $optionsStatus[$key] = $temp;
    }
}

$optionsAction = array();
// $actionStatus[] = '---';
// $actionStatus['0'] = '自定义';
foreach ($actionStatus as $key => $value) {
    $temp = array(
        'label' => $value
    );
    if ($key != $model->status && $model->status > 10 && !isset($listStatus[$key])) {
        $temp['url'] = 'javascript:setStatus(' . $key . ',"' . $value . '");';
    }
    $optionsAction[$key] = $temp;
}
$optionsAction[] = '---';
$optionsAction['-1'] = array(
    'label' => '自定义',
    'htmlOptions' => array(
        'style' => 'color:red'
    )
);

if ($model->status < 999 && $model->status > 10) {
    $optionsAction['-1']['url'] = 'javascript:setStatus(0,"自定义");';
    $optionsAction[] = array(
        'label' => '关闭',
        'url' => 'javascript:setStatus("","");'
    );
} else {
    $optionsAction[] = '---';
    $optionsAction[] = array(
        'label' => '订单未审核状态,不可操作,请先审核订单',
        'htmlOptions' => array(
            'style' => 'color:#000;'
        ) ,
        'url' => 'javascript:;'
    );
}

$iupload = $this->widget('ext.Plupload.PluploadWidget', array(
    'config' => array(
        'pluploadPath' => true,
        'url' => $this->createUrl('/it/upload') ,
        'browse_button' => 'pickfiles',
        'container' => 'container',
    ) ,
    'id' => 'uploader'
) , true);

Tak::regScript('orders-updates', sprintf('var takurls={updatePrice:"%s",updates:"%s",postFileUrl:"%s",iupload:"%s"};', $this->createUrl('updatePrice', array(
    'id' => $id
)) , $this->createUrl('updates', array(
    'id' => $id
)) , $this->createUrl('/it/upload') , $iupload) , CClientScript::POS_HEAD);
$this->regScriptFile('k-load-order-updates.js', CClientScript::POS_END);
?>

<div class="tak-order-status">
  <?php echo CHtml::image($this->getAssetsUrl() . 'img/tak/' . $model->status . '.png') ?>
</div>
<div class="well">
  <strong><?php echo $model->getAttributeLabel('itemid'); ?></strong>：
  <?php echo $model->itemid; ?>
  ，
  <strong><?php echo $model->getAttributeLabel('status'); ?></strong>：
  <?php echo OrderType::item('order-status', $model->status); ?>
  ，
  <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'htmlOptions' => array(
        'id' => 'list-status',
    ) ,
    'buttons' => array(
        array(
            'label' => '订单状态处理',
            'items' => $optionsStatus
        ) ,
    ) ,
)); ?>
  <strong class="red">第1步</strong>
  <p>
    <strong><?php echo $model->getAttributeLabel('add_time'); ?></strong>：
  <?php echo Tak::timetodate($model->add_time, 6); ?>
  ，
  <strong><?php echo $model->getAttributeLabel('total'); ?></strong>：
  <strong class="price-strong">
  <?php echo Tak::format_price($model->total); ?>
  </strong>
  ，
  <strong><?php echo $model->getAttributeLabel('manageid'); ?></strong>：
  <?php
$company = $model->company;
if (isset($model->iManage)) {
    $str = '<i class="icon-eye-open"></i>' . $model->company;
    $company = CHtml::link($str, Yii::app()->createUrl('/Site/PreviewTestMember', array(
        'id' => Tak::setSId($model->manageid)
    )) , array(
        'class' => 'data-ajax',
        'title' => $model->company
    ));
} elseif ($company == '') {
    $company = '未知';
}
echo $company;
?>
  ，
  <strong><?php echo $model->getAttributeLabel('add_ip'); ?></strong>：
  <?php echo Tak::Num2IP($model->add_ip); ?>
  </p>
  <p>
  <?php
if ($model->pay_time > 0) {
    echo '<strong>', $model->getAttributeLabel('pay_time') , '</strong>：', Tak::timetodate($model->pay_time, 6) , '，';
}
if ($model->delivery_time > 0) {
    echo '<strong>', $model->getAttributeLabel('delivery_time') , '</strong>：', Tak::timetodate($model->delivery_time, 6);
}

?>
  </p>
  <?php if ($model->status >1): ?>
  <p>
  <strong><?php echo $model->getAttributeLabel('serialid'); ?></strong>：
  <span><?php echo $model->serialid; ?></span>
  ，
  <strong><?php echo $model->getAttributeLabel('cnote'); ?></strong>：
  <span id="data-cnote"><?php echo $model->cnote; ?></span>
  &nbsp;&nbsp;&nbsp;
  <a href="<?php echo $this->createUrl('upData', array(
        'id' => $id
    )) ?>" class="edit-row"><i class="icon-pencil"></i>修改</a>
  </p>
<?php
endif
?>
  <div class="wide">
    <?php
$flow = new OrderFlow;
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'flow-form',
    'action' => $this->createUrl('flowset', array(
        'id' => $id
    )) ,
    /*
    // 'htmlOptions' => array('class'=>'flow-form'),
    */
)); ?>
    <?php echo $form->hiddenField($flow, 'status'); ?>
    <?php echo $form->hiddenField($flow, 'itemid'); ?>
    <input type="hidden" disabled="">
    <table class="tak-table">
      <caption>
      <strong class="red">第2步</strong>
      <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'buttons' => array(
        array(
            'label' => ' 订单流程操作 ',
            'items' => $optionsAction
        ) ,
    ) ,
)); ?>
      &nbsp;&nbsp;
      <span class="label label-info" id="show-status"></span>
      </caption>
      <tbody class="wap-flow-content">
        <tr>
          <th><?php echo $form->label($flow, 'action_user'); ?></th>
          <td>
            <?php echo $form->textField($flow, 'action_user', array(
    'size' => 100,
    'maxlength' => 10,
    'required' => 'required'
)); ?>
          </td>
          <th><?php echo $form->label($flow, 'name'); ?></th>
          <td>
            <?php echo $form->textField($flow, 'name', array(
    'size' => 60,
    'maxlength' => 60,
    'disabled' => 'disabled',
    'required' => 'required'
)); ?>
          </td>
        </tr>
        <tr>
          <th>文件列表</th>
          <td>
            <?php
?>
            <div id="container" class="itak-dr">
              <a id="pickfiles" href="#">[选择文件]</a>
              <div class="dr"><span></span></div>
              <div id="filelist"></div>
            </div>
          </td>
          <th><?php echo $form->label($flow, 'note'); ?></th>
          <td>
            <?php echo $form->textArea($flow, 'note', array(
    'size' => 60,
    'maxlength' => 255
)); ?>
          </td>
        </tr>
        <tr>
          <th></th>
          <td colspan="3">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g('Save')
)); ?>
          </td>
        </tr>
      </tbody>
    </table>
    <?php $this->endWidget(); ?>


    <table class="tak-table action-fold">
      <caption>订单跟踪</caption>
      <colgroup align="center">
      <col width="140px"/>
      <col width="85px"/>
      <col width="150px"/>
      </colgroup>
      <thead>
        <tr>
          <th>处理时间</th>
          <th>操作人</th>
          <th>状态</th>
          <th>文件</th>
          <th>备注详情</th>
        </tr>
      </thead>
      <tbody class="wap-products">
        <?php
$list = $model->getFlows();
$result = '';
$strHtml = '<tr>
          <td>:add_time</td>
          <td>:action_user</td>
          <td>:name</td>
          <td>:pics</td>
          <td>:note</td>
        </tr>';
$arr = false;
foreach ($list as $key => $value) {
    $arr = array(
        ':pics' => $value->getFilesImg() ,
        ':add_time' => Tak::timetodate($value->add_time, 6) ,
        ':action_user' => $value->action_user,
        ':name' => $value->getName() ,
        ':note' => $value->note,
    );
    $result.= strtr($strHtml, $arr);
}
echo $result;
?>
      </tbody>
    </table>

  </div>

  <?php if ($orderInfo): ?>

  <table class="tak-table action-fold">
    <caption>详细信息</caption>
    <tbody>
      <tr>
        <th><?php echo $orderInfo->getAttributeLabel('date_time'); ?>:</th>
        <td><?php echo Tak::timetodate($orderInfo->date_time, 3); ?></td>
        <th><?php echo $orderInfo->getAttributeLabel('packing'); ?>:</th>
        <td><?php echo OrderType::item('packing', $orderInfo->packing); ?></td>
      </tr>
      <tr>
        <th><?php echo $orderInfo->getAttributeLabel('taxes'); ?>:</th>
        <td><?php echo OrderType::item('taxes', $orderInfo->taxes); ?></td>
        <th><?php echo $orderInfo->getAttributeLabel('convey'); ?>:</th>
        <td><?php echo OrderType::item('convey', $orderInfo->convey); ?></td>
      </tr>
      <tr>
        <th><?php echo $orderInfo->getAttributeLabel('pay_type'); ?>:</th>
        <td colspan="3">
          <?php
    echo $pay_type['title'];
    echo $orderInfo->getPayInfo($model->total);
?>
        </td>
      </tr>
      <tr>
        <th><?php echo $orderInfo->getAttributeLabel('detype'); ?>:</th>
        <td colspan="3">
          <?php
    echo OrderType::getStatus('detype', $orderInfo->detype);
    echo $orderInfo->getContactp();
?>
        </td>
      </tr>
      <tr>
        <th><?php echo $orderInfo->getAttributeLabel('note'); ?>:</th>
        <td colspan="3">
          <?php
    echo $orderInfo->note;
?>
        </td>
      </tr>
    </tbody>
  </table>
  <?php
endif
?>

  <table class="tak-table action-fold">
    <caption>商品清单</caption>
    <thead>
      <tr>
        <th width="150">产品名称</th>
        <th>详情</th>
        <th width="150">单价(双击价格进行调整)</th>
        <th width="80">数量</th>
        <th width="150">小计</th>
      </tr>
    </thead>
    <tbody class="wap-products">
      <?php
$list = $model->getProducts();
$result = '';
$strHtml = '<tr>
        <td>:name</td>
        <td>
          <dl>
            <dt>$model:</dt><dd>:model &nbsp;</dd>
            <dt>$standard:</dt><dd>:standard &nbsp;</dd>
            <dt>$color:</dt><dd>:color &nbsp;</dd>
            <dt>$unit:</dt><dd>:unit &nbsp;</dd>
          </dl>
          <div class="kclear"></div>
          <div>
            <strong>$note:</strong>
            :note
          </div>
          :pics
        </td>
        <td class="price-strong ajax-price" id=":itemid" data-value=":price" title="双击修改" >
          ￥:price
        </td>
        <td>:amount</td>
        <td class="price-strong">￥:sum</td>
      </tr>';
$arr = false;
foreach ($list as $key => $value) {
    $arr = array(
        ':pics' => $value->getFilesImg()
    );
    $icount = 0;
    foreach (array(
        'itemid',
        'name',
        'amount',
        'price',
        'sum',
        'unit',
        'model',
        'standard',
        'color',
        'note'
    ) as $v1) {
        $arr[':' . $v1] = $value->{$v1};
        if ($icount > 3) {
            $arr['$' . $v1] = $value->getAttributeLabel($v1);
        }
        $icount++;
    }
    $result.= strtr($strHtml, $arr);
}
echo $result;
?>
    </tbody>
    <tfoot>
    <tr>
      <td colspan="5">合计:
        <strong  class="price-strong">￥
        <?php echo $model->total; ?>
        </strong>
      </td>
    </tr>
    </tfoot>
  </table>

  <?php $this->beginWidget('bootstrap.widgets.TbModal', array(
    'options' => array(
        'show' => false,
    ) ,
    'id' => 'modalStatusWap'
)); ?>
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>...</h4>
  </div>
  <?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'status-form',
    'action' => $this->createUrl('status') ,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    ) ,
)); ?>
  <?php echo $form->hiddenField($flow, 'status'); ?>
  <?php echo $form->hiddenField($flow, 'itemid'); ?>
  <div class="modal-body">
  <div  class="hide tak-data-rows">
    <div class="row-form clearfix">
      <div class="control-group ">
        <label class="control-label" for="mserialid">工单号</label>
        <div class="controls">
          <input type="text"  name="serialid" id="mserialid" value="<?php echo $model->serialid; ?>" />
        </div>
      </div>
    </div>
    <div class="row-form">
      <div class="control-group ">
        <label class="control-label" for="mcnote">商家备注</label>
        <div class="controls">
          <textarea name="cnote" id="cnote"><?php echo $model->cnote; ?></textarea>
        </div>
      </div>
  </div>
</div>
    <div class="row-form clearfix tak-data-rows hide">
      <div class="control-group ">
        <label class="control-label" for="mnote">备注详情</label>
        <div class="controls">
          <textarea name="note" id="mnote"></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn">保存</button>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'label' => '取消',
    'url' => '#',
    'htmlOptions' => array(
        'data-dismiss' => 'modal'
    ) ,
)); ?>
  </div>
  <?php $this->endWidget(); ?>
  <?php $this->endWidget(); ?>
</div>