<?php
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-form',
    'type' => 'search',
    'htmlOptions' => array(
        'class' => 'well'
    ) ,
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));

if (!isset($notcate)) {
?>
<span class="span2">
<?php
    $this->renderPartial('/category/select', array(
        'id' => sprintf("%s[typeid]", $model->mName) ,
        'value' => $model->typeid,
    ));
?>
</span>
<?php
}
if (isset($warehouse)) {
    /*
    echo JHtml::dropDownList('warehouse_id', $_GET['warehouse_id'], Warehouse::toSelects(Tk::g(array('All','Warehouse')),Permission::iSWarehouses()));
    */
    $warehouse_id = $model->warehouse_id;
    if (is_array($warehouse_id)) {
        $model->warehouse_id = - 1;
    }
    if ($typeid == 2) {
        $warehouse_label = 0;
        echo JHtml::hiddenField('type',Tak::setCryptKey($typeid));
    } else {
        $warehouse_label = Tk::g(array(
            'All',
            'Warehouse'
        ));
    }
    echo $form->dropDownList($model, 'warehouse_id', Warehouse::toSelects($warehouse_label, Permission::iSWarehouses()));
    $model->warehouse_id = $warehouse_id;
}
echo $form->textFieldRow($model, 'name', array(
    'size' => 10,
    'maxlength' => 10
));
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Tk::g('Search')
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'button',
    'label' => Tk::g('More') ,
    'htmlOptions' => array(
        'class' => 'btn-more-serch'
    )
));
?>
<div id="list-more-search" class="hide">
<ul>
  <li>
    <?php echo CHtml::activeLabelEx($model, 'material') ?>:
    <?php echo CHtml::activeTextField($model, 'material'); ?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model, 'spec') ?>:
    <?php echo CHtml::activeTextField($model, 'spec'); ?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model, 'price') ?>:
    <?php echo CHtml::activeTextField($model, 'price'); ?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model, 'color') ?>:
    <?php echo CHtml::activeTextField($model, 'color'); ?>
  </li>
  <li>
    <?php echo CHtml::activeLabelEx($model, 'note') ?>:
    <?php echo CHtml::activeTextField($model, 'note'); ?>
  </li>
</ul>
</div>
<?php $this->endWidget(); ?>   