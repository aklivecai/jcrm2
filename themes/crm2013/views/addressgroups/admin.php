<?php
$this->breadcrumbs = array(
    Tk::g('Address Groups') => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);
?>
<div class="page-header">
      <h1><?php echo Tk::g('Address Groups'); ?> <small>显示状态，表示是否在前台显示的组</small></h1>
</div>
<div class="block-fluid">
    <div class="row-fluid">
<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'brand' => '',
    'brandUrl' => '#',
    'fixed' => 'false',
    'fixed' => 'true',
    'collapse' => true, // requires bootstrap-responsive.css
    
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
                array(
                    'label' => Tk::g(array(
                        'Add',
                        'Address Groups'
                    )) ,
                    'url' => array(
                        $this->modelName . '/Create'
                    ) ,
                    'active' => true,
                    'linkOptions' => array(
                        'class' => "data-ajax",
                        'title' => Tk::g(array(
                            'Add',
                            'Address Groups'
                        ))
                    )
                ) ,
                array(
                    'label' => Tk::g(array(
                        'Update',
                        'Address Groups'
                    )) ,
                    'url' => array(
                        $this->modelName . '/Update'
                    ) ,
                    'linkOptions' => array(
                        'title' => Tk::g(array(
                            'Update',
                            'Address Groups'
                        )) ,
                        'class' => 'data-ajax',
                        'id' => "ajax-update"
                    )
                ) ,
                
                array(
                    'label' => Tk::g(array(
                        'Delete',
                        'Address Groups'
                    )) ,
                    'url' => 'Delete',
                    'linkOptions' => array(
                        'id' => "data-deletd"
                    )
                ) ,
            ) ,
        ) ,
    ) ,
));

$options = array(
    'type' => 'striped bordered condensed',
    'id' => 'list-grid',
    'dataProvider' => $data,
    'enableHistory' => false,
    'afterAjaxUpdate' => 'kloadCGridview',
    'loadingCssClass' => 'grid-view-loading',
    'template' => '{items}',
    'ajaxUpdate' => false, //禁用AJAX
    'enableSorting' => false,
    'selectableRows' => false,
    'summaryText' => '',
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'name' => 'address_groups_id',
            'selectableRows' => 1,
            'headerTemplate' => Tk::g('Select') ,
            'selectableRows' => 2,
            'headerHtmlOptions' => array(
                'width' => '45px',
                'align' => 'center'
            ) ,
            'checkBoxHtmlOptions' => array(
                'name' => 'itemid[]',
                'align' => 'center',
            ) ,
        ) ,
        array(
            'name' => $model->getAttributeLabel('display') ,
            'type' => 'raw',
            'value' => 'TakType::getStatus("display",$data[display])',
            'headerHtmlOptions' => array(
                'style' => 'width: 45px'
            ) ,
        ) ,
        array(
            'name' => $model->getAttributeLabel('name') ,
            'value' => '$data[name]',
        ) ,
        array(
            'name' => $model->getAttributeLabel('note') ,
            'type' => 'raw',
            'value' => '$data[note]'
        ) ,
        array(
            'name' => $model->getAttributeLabel('listorder') ,
            'value' => '$data[listorder]',
            'headerHtmlOptions' => array(
                'style' => 'width: 45px'
            ) ,
        ) ,
        array(
            'name' => Tk::g(array(
                'View',
                'AddressBook'
            )) ,
            'type' => 'raw',
            'value' => 'JHtml::link("",Yii::app()->createUrl("AddressBook/Admin",array("AddressBook[groups_id]"=>$data[address_groups_id])),array("class"=>"icon-eye-open"))',
            'headerHtmlOptions' => array(
                'style' => 'width: 85px'
            ) ,
        ) ,
    )
);

$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
</div>
</div>

<?php
Tak::regScript('bodyend-', '
    $("input[value=' . $id . ']").prop("checked");
    var CURL = "' . Yii::app()->createUrl("AddressGroups") . '";
    var getRows = function(){
        var data = [];
            $("input:checkbox[name=\'itemid[]\']").each(function (){
                if($(this).attr("checked")){
                    data.push($(this).val());
                }
            });
        return data;
    }
    , caction = function(){
        var data = getRows();
        if ( data.length == 0) {
            alert("未选择要操作的组");
            return false;
        }      
        if (data.length>=2) {
            alert("只能选择1个组");
            return false;
        }      
        return data[0];
    }
    ;
    $("#ajax-update").on("click",function(event){
        var t = $(this)
            , data = caction()
            ;
        if (!data) {
            event.preventDefault();
            return false;
        }
        url = CURL+"/Update/"+data;
        t.attr("href",url);
    });

    $("#data-deletd").on("click",function(event){
        event.preventDefault();
        var t = $(this)
            , data = caction()
            ;
        if (!data) {
            return false;
        }
        if (sCF("确定删除该组？")) {
            return false;
        }  
        url = CURL+"/delete/"+data;
        $.ajax({url:url}).done(function(data) {
            if(data==""||data=="ok"){
                window.location.href = CURL;
            }else{
                alert(data);
            }
        });
    })
        $(".data-listorder").on("click",function(event){
            event.preventDefault();
            var t = $(this)
                , url = t.attr("href").split("?")
                , data = caction()
                ;
            if (!data) {
                return false;
            }
            url = CURL+"/listorder/"+data;
            url+="?action="+t.attr("data-action");            
            window.location.href = url;
        })
');
?>