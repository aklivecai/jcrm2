<?php $this->beginContent(Rights::module()->appLayout);
$temps = array(
    Tk::g('Workflow') => array(
        'default/index'
    )
);
foreach ($this->breadcrumbs as $key => $value) {
    $temps[$key] = $value;
}
$this->breadcrumbs = $temps;

$controlId = $this->id;

$navs = array(
    'my' => array(
        'icon' => 'user',
        'label' => Tk::g('My Workflow') ,
        'url' => array(
            '/workflow/default/index'
        ) ,
        'visible' => true,
    ) ,
    array(
        'icon' => 'list',
        'label' => Tk::g('My Neet') ,
        'url' => array(
            '/workflow/default/myneet'
        ) ,
        'itemOptions' => array(
            'class' => 'remind'
        )
    ) ,
    array(
        'icon' => 'list',
        'label' => Tk::g('Handle') ,
        'url' => array(
            '/workflow/default/handle'
        )
    ) ,
    array(
        'icon' => 'list',
        'label' => Tk::g('Completed') ,
        'url' => array(
            '/workflow/default/completed'
        )
    ) ,
    'setting' => array(
        'icon' => ' book',
        'label' => Tk::g(array(
            'Workflow',
            'Setting'
        )) ,
        'url' => array(
            '/workflow/production/index'
        ) ,
        'active' => ($controlId == 'production') ,
        'visible' => true,
    ) ,
);

$subItems = array(
    'label' => Tk::g('Entering') ,
    'url' => $this->createUrl('create') ,
    'icon' => 'plus'
);
$_subItems = array();
foreach (array() as $key => $value) {
    $_subItems[] = array(
        'label' => $value,
        'url' => $this->createUrl('create', array(
            'Movings[typeid]' => $key
        )) ,
        'icon' => 'isw-text_document'
    );
}

$listMenu = array(
    'Create' => array(
        'icon' => 'isw-plus',
        'url' => array(
            'create'
        ) ,
        'label' => Tk::g('Entering') ,
        'items' => $_subItems,
        'submenuOptions' => array(
            'class' => 'dd-list'
        ) ,
    )
);
?>

<div class="block-fluid">
    <div class="row-fluid">
<?php
$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => '',
    'brandUrl' => '#',
    'fixed' => 'true',
    'collapse' => true,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $navs,
        ) ,
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array(
                'class' => 'pull-right'
            ) ,
            'items' => array(
                array(
                    'icon' => 'isw-plus',
                    'label' => Tk::g('Apply') ,
                    'url' => '#',
                    'items' => $this->listWorks,
                ) ,
            ) ,
        ) ,
    ) ,
));
echo $content;
?>
</div>
</div>
<?php $this->endContent(); ?>