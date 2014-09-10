<?php
/* @var $this ClienteleController */
/* @var $model Clientele */
/* @var $form CActiveForm */
?>
<div class="row-fluid">
<?php
$items = array(
    'product' => array(
        'label' => Tk::g('Product') ,
        'url' => Yii::app()->createUrl('Product/Admin')
    ) ,
    'taktype' => array(
        'label' => Tk::g('Product Type') ,
        'url' => Yii::app()->createUrl('Category/Admin', array(
            'm' => 'product'
        )), 'visible' => Tak::checkAccess('Category.*') ,
    ) ,
);
if (isset($items[$this->getId() ])) {
    $items[$this->getId() ]['active'] = true;
} elseif ($this->id == 'taktype') {
    $items[$this->getId() ]['active'] = true;
}
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked' => false, // whether this is a stacked menu
    'items' => $items
)); ?>
</div>
