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
        'visible' => Tak::checkAccess('Workflow.*') ,
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

if (Tak::checkSuperuser()) {
    echo '&nbsp;&nbsp;','切换账号:', JHtml::hiddenField('', Tak::getCid() , array(
        'id' => 'changeMid',
        'class' => 'select-manageid',
        'placeholder' => '模拟切换用户',
        'style' => 'width:250px',
    ));
}
echo $content;
?>
</div>
</div>
<script type="text/javascript">
    +jQuery(function($) {
        $.ajax({
             type: "get",
             async: false,
             url: createUrl('workflow/default/GetRunNum'),
             dataType: "json",
             success: function(json){
                 if (json.status==1) {
                    $('.remind').addClass('exist-info');
                 };
             }
         });        
        $('#changeMid').on('change',function(){
            iAjax({
                url: createUrl('workflow/default/ChangeUser/'+$('#changeMid').val())
            });            
        });
    })
</script>
<?php $this->endContent(); ?>