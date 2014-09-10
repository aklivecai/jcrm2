<?php
$modelName = Tk::g($this->modelName);
$this->breadcrumbs = array(
     $modelName => array(
        'admin'
    )
);

?>
<div class="block-fluid">
    <div class="row-fluid">
<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'=>'',
    'brandUrl'=>'#',
    'fixed'=>'false',
    'fixed'=>'true',
    'collapse'=>true, // requires bootstrap-responsive.css

    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>Tk::g(array('Add',$modelName)), 'url'=>array($this->modelName.'/Create'), 'active'=>true,'linkOptions'=>array('class'=>"data-ajax",'title'=>Tk::g(array('Add',$modelName)))),
                array('label'=>Tk::g(array('Update',$modelName)), 'url'=>array($this->modelName.'/Update'),'linkOptions'=>array('title'=>Tk::g(array('Update',$modelName)),'class'=>'data-ajax','id'=>"ajax-update")),

                array('label'=>Tk::g(array('Delete',$modelName)), 'url'=>'Delete','linkOptions'=>array('id'=>"data-deletd")),

                array('label'=>Tk::g(':name move up',array(':name'=>$modelName)), 'url'=>array($this->modelName.'/Listorder'),'linkOptions'=>array('data-action'=>"up",'class'=>"data-listorder")),
                array('label'=>Tk::g(':name move dw',array(':name'=>$modelName)), 'url'=>array($this->modelName.'/Listorder'),'linkOptions'=>array('data-action'=>"dw",'class'=>"data-listorder")),
            ),
        ),
    ),
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
                            'class'=>'CCheckBoxColumn',
                            'name'=>'itemid',
                            'selectableRows'=>1,
                            'headerTemplate'=>Tk::g('Select'),
                            'selectableRows' => 2,
                            'headerHtmlOptions' => array('width'=>'45px','align'=>'center'),
                            'checkBoxHtmlOptions' => array(
                                'name' => 'itemid[]',
                                'align'=>'center',
                            ),        

                    ),
                    array('name'=>$model->getAttributeLabel('name'),'type'=>'raw','value'=>'$data[name]'),
                    array('name'=>$model->getAttributeLabel('user_name'),'type'=>'raw','value'=>'$data[user_name]'),
                    array('name'=>$model->getAttributeLabel('telephone'),'type'=>'raw','value'=>'$data[telephone]'),                    
                    array('name'=>$model->getAttributeLabel('note'),'type'=>'raw','value'=>'$data[note]'),
                )
            );

    $widget = $this->widget('bootstrap.widgets.TbGridView', $options); 
?>
</div>
</div>

<?php

Tak::regScript('bodyend-','
    $("input[value='.$id.']").prop("checked");
    var CURL = "'.Yii::app()->createUrl("Warehouse").'";
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
            alert("未选择要操作的仓库");
            return false;
        }      
        if (data.length>=2) {
            alert("只能选择1个仓库");
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
        url = CURL+"/update/"+data;
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
        if (sCF("确定删除该仓库？")) {
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
        // return false;
        // $.ajax({url:url}).done(function(data) {});
        // "该仓库已经有出入库，不能进行删除"
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