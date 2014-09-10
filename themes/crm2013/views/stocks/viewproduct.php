<?php
/* @var $this StocksController */
/* @var $model Stocks */
$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'index'
    ) ,
    $model->getLinkName() ,
);
?>
<div class="">
<?php
$msgProduct = '<div class="span5">';
$msgProduct.= $this->renderPartial('//product/_view', array(
    'model' => $model
) , true);
$msgProduct.= '</div><div class="span5">';

$msgProduct.= $this->renderPartial('_stock', array(
    'model' => $model
) , true);

$msgProduct.= '</div>';
$items = array(
    'product' => array(
        'label' => Tk::g(array(
            'Product',
            'Detail'
        )) ,
        'content' => $msgProduct
    ) ,
);

foreach (array(
    1 => 'purchase',
    2 => 'sell'
) as $key => $value) {
    $items[$value] = array(
        'label' => Tk::g(array(
            ucwords($value) ,
            'Detail'
        )) ,
        'content' => $datas[$value]
    );
}

$isactive = isset($_GET['tab']) && isset($_GET['tab']) ? $_GET['tab'] : false;

if ($isactive && $items[$isactive]) {
    $items[$isactive]['active'] = true;
} else {
    $items['product']['active'] = true;
}
$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs', // 'tabs' or 'pills'
    'placement' => 'above', // 'above', 'right', 'below' or 'left'
    'tabs' => $items,
));
?>
</div>