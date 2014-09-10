<?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'search-form',
    'htmlOptions' => array(
        'class' => 'form-search'
    ) ,
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));

$types = array(
    '-1' => $model->getAttributeLabel('typeid')
);
foreach ($cates as $key => $value) {
    $types[$key] = $value;
}

if (!isset($_GET['time'])) {
    $_GET['time'] = array(
        'time' => array(
            '',
            ''
        )
    );
}
!isset($_GET['info-product']) && $_GET['info-product'] = '';
?>

<table class="table table-search">
  <colgroup align="center">
  <col width="90px"/>
  <col width="125px"/>
  <col width="90px"/>
  <col width="125px"/>
  </colgroup>
  <tbody>
    <tr>
      <th></th>
      <td colspan="4">
<?php
echo $form->dropDownList($model, 'typeid', $types);

$warehouse_id = $model->warehouse_id;
if (is_array($warehouse_id)) {
    $model->warehouse_id = - 1;
}
echo $form->dropDownList($model, 'warehouse_id', Warehouse::toSelects(Tk::g(array(
    'All',
    'Warehouse'
)) , Permission::iSWarehouses()));
$model->warehouse_id = $warehouse_id;
?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form::label($model, 'time') ?></th>
      <td colspan="3">
        <?php echo CHtml::textField('time[time][]', $_GET['time']['time']['0'], array(
    'class' => 'type-date',
    'id' => 'time-start'
)); ?>
        至
        <?php echo CHtml::textField('time[time][]', $_GET['time']['time']['1'], array(
    'class' => 'type-date',
    'id' => 'time-end'
)); ?>
      </td>
      <td></td>
    </tr>
  </tbody>
  <tbody  class="more-content hide">
    <tr>
    <th><?php echo CHtml::label('产品', 'info-product') ?></th>
    <td><?php echo CHtml::textField('info-product', $_GET['info-product']); ?></td>
          <th>
        <?php echo CHtml::activeLabelEx($model, 'numbers') ?>
      </th>
      <td colspan="2">
        <?php echo CHtml::activeTextField($model, 'numbers'); ?>
      </td>
    </tr>
    <tr>
      <th><?php echo CHtml::activeLabelEx($model, 'enterprise') ?></th>
      <td><?php echo CHtml::activeTextField($model, 'enterprise'); ?></td>
      <th><?php echo CHtml::activeLabelEx($model, 'us_launch') ?></th>
      <td colspan="2"><?php echo CHtml::activeTextField($model, 'us_launch'); ?></td>
    </tr>
    <tr>
      <th><?php echo CHtml::activeLabelEx($model, 'note') ?>:</th>
      <td colspan="4"><?php echo CHtml::activeTextField($model, 'note'); ?></td>
    </tr>
  </tbody>
  <tfoot>
  <tr>
    <th></th>
    <td colspan="4">
      <?php echo JHtml::htmlButton(Tk::g('Search') , array(
    'class' => 'btn',
    'type' => 'submit'
)) ?>
      <?php echo JHtml::htmlButton(Tk::g('More') , array(
    'class' => 'btn btn-more'
)) ?>
    </td>
  </tr>
  </tfoot>
</table>
<?php
$this->endWidget();
?>