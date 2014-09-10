<?php
/* @var $this OrderController */
/* @var $model Order */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
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
// $listStatus = $model->getListStatus();

$pay_type = OrderConfig::getAlipay($orderInfo->pay_type, $fromid);
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
<p>
	<?php echo $model->getAttributeLabel('add_time'); ?>：
	<?php echo Tak::timetodate($model->add_time, 6); ?>
	，
	<?php echo $model->getAttributeLabel('total'); ?>：
	<strong class="price-strong">
		<?php echo Tak::format_price($model->total); ?>
	</strong>
	，
	<?php echo $model->getAttributeLabel('manageid'); ?>：
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
	<!--，
	<?php echo $model->getAttributeLabel('add_ip'); ?>：
	<?php echo Tak::Num2IP($model->add_ip); ?>
	-->
</p>
<p>
	<?php
if ($model->pay_time > 0) {
    echo $model->getAttributeLabel('pay_time') . '：' . Tak::timetodate($model->pay_time, 6) . '，';
}
if ($model->delivery_time > 0) {
    echo $model->getAttributeLabel('delivery_time') . '：' . Tak::timetodate($model->delivery_time, 6);
}
?>
</p> 
  <?php if ($model->status >= 101): ?>
  <p>
  <strong><?php echo $model->getAttributeLabel('serialid'); ?></strong>：
  <span><?php echo $model->serialid; ?></span>
  ，
  <strong><?php echo $model->getAttributeLabel('cnote'); ?></strong>：
  <span id="data-cnote"><?php echo $model->cnote; ?></span>
  </p>
<?php
endif
?>

<?php if ($model->isStatusOver()): ?>
<table class="tak-table">
	<caption>订单评价</caption>
	<colgroup align="center">
	<col width="100px" />
	</colgroup>
	<tbody>
<?php
    if($orderReview):
?>
<tr>
	<th>内容
	<br />
	<?php echo Tak::timetodate($orderReview['add_time'], 4) ?>
	</th>
	<td><?php echo JHtml::encode($orderReview['content']) ?></td>
</tr>
<?php
    else:
        echo "<tr><td>客户还没有进行评价!</td></tr>";
?><?php
    endif
?>
</tbody>
</table>
<?php
endif
?>

<?php
$this->renderPartial('_info', array(
    'model' => $model,
    'orderInfo' => $orderInfo,
    'pay_type' => $pay_type,
));
?>