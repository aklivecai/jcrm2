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
    <th>
      <?php echo CHtml::activeLabelEx($model, 'name') ?>
      </th>
      <td>
        <?php echo CHtml::activeTextField($model, 'name') ?>
      </td>
    <th><?php echo CHtml::label('核算产品', 'info-product') ?></th>
    <td><?php echo CHtml::textField('info-product', $_GET['info-product']); ?></td>
      <td >
      </td>
    </tr>
            <tr>
            <th><?php echo $form->label($model, 'totals') ?></th>
            <td colspan="2">
                <?php echo CHtml::dropDownList('comparison', Tak::getQuery('comparison') , TakType::items('comparison')) ?>
                <?php echo $form->textField($model, 'totals'); ?>
            </td>
            <td colspan="2"></td>
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
    </td>
  </tr>
  </tfoot>
</table>
<?php
$this->endWidget();
?>