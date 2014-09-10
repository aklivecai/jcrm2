<?php
$this->breadcrumbs = array(
    Tk::g($this->getType() . ' Category') => $this->cateUrl,
    Tk::g('Admin') ,
);
$items = array(
    array(
        'icon' => 'isw-plus',
        'url' => $this->getLink('Create') ,
        'label' => Tk::g('Create') ,
    )
);
?>
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
                    'label' => Tk::g('Add') ,
                    'url' => $this->getLink('Create') ,
                    'active' => true,
                    'linkOptions' => array(
                        'class' => "data-ajax",
                        'id' => 'create-category',
                        'title' => Tk::g('Add')
                    )
                ) ,
                array(
                    'label' => Tk::g('Update') ,
                    'url' => $this->getLink('Update') ,
                    'linkOptions' => array(
                        'title' => Tk::g('Update') ,
                        'class' => 'data-ajax',
                        'id' => "ajax-update"
                    )
                ) ,
                
                array(
                    'label' => Tk::g('Delete') ,
                    'url' => $this->getLink('Delete') ,
                    'linkOptions' => array(
                        'id' => "data-deletd"
                    )
                ) ,
            ) ,
        ) ,
    ) ,
));
$this->renderPartial('_show', array(
    'model' => $model,
    'id' => $id,
    'action' => $action,
));
Tak::regScript('xxx', '
    var CURL = "' . $this->getLink('Admin') . '";
    var caction = function(){
        var data = jstrss.jstree("get_selected");
        if ( data.length == 0) {
            alert("未选择要操作的分类");
            return false;
        }    
        return data[0];          
    }
$("#create-category").on("click",function(event){
    var data = jstrss.jstree("get_selected")
    , t = $(this)
    ;
    if (data.length>0) {
        url = t.attr("href")+"&Category[parentid]="+data[0];
        t.attr("href",url);        
    }
});
$("#data-deletd").on("click",function(event){
        event.preventDefault();
        var t = $(this)
            , data = caction()
            ;
        if (!data) {
            return false;
        }
        if (sCF("确定删除该分类？")) {
            return false;
        }                      
        url = t.attr("href")+"&id="+data;
        $.ajax({url:url}).done(function(data) {
            if(data==""||data=="ok"){
                window.location.href = CURL;
            }else{
                alert(data);
            }
        });
});
$("#ajax-update").on("click",function(event){
     event.preventDefault();
     var t = $(this)
    var data = caction();
        if (!data) {
            event.preventDefault();
            return false;
        }
        url = t.attr("href")+"&id="+data;
        t.attr("href",url);
});   

$(document).on("dblclick","#jstree_category a",function(){
  var id = caction();
  if (id>0) {
      $("#ajax-update").trigger("click");
  }
});
',CClientScript::POS_END);
?>

</div>
</div>



