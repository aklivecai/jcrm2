<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => '',
    'brandUrl' => '#',
    'fixed' => 'false',
    'fixed' => 'true',
    'collapse' => true,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $tabs,
        ) ,
    ) ,
));
?>