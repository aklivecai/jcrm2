<?php
/* @var $this ProductController */
/* @var $model Product */
$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);
$items = Tak::getListMenu();
$this->regScriptFile('linq.js');
$scrpitS = array(
    '_ak/js/advanced/linq.min.js',
);
Tak::regScriptFile($scrpitS, 'static');
?>
<h1>选择产品</h1>
<div class="row-fluid">
    <div class="block-fluid clearfix">
<?php
$tags = $model->search();
$m = $tags->getData();

$this->renderPartial("_search", array(
    'model' => $model,
    'warehouse' => true,
    'typeid' => $typeid
));

$options = Tak::gredViewOptions(false);
$options["afterAjaxUpdate"] = null;
$options['dataProvider'] = $tags;
$options['ajaxUpdate'] = false;
$columns = array(
    array(
        'class' => 'CCheckBoxColumn',
        'name' => 'itemid',
        'selectableRows' => 1,
        'headerTemplate' => '<input type="checkbox" id="select_all">',
        'selectableRows' => 2,
        'headerHtmlOptions' => array(
            'width' => '25px',
            'align' => 'center'
        ) ,
        'checkBoxHtmlOptions' => array(
            'name' => 'itemid[]',
            'align' => 'center',
            'class' => 'check',
        ) ,
    ) ,
    'name',
    array(
        'name' => 'typeid',
        'type' => 'raw',
        'value' => 'Category::getProductName($data->typeid," / ")',
    ) ,
    array(
        'name' => 'material',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'spec',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'color',
        'type' => 'raw',
    ) ,
    array(
        'name' => 'price',
        'type' => 'raw',
        'value'=>'Tak::format_price($data->price)',
        'htmlOptions' => array(
            'style' => 'width:120px;'
        ) ,
    )
);
$options['columns'] = $columns;
$widget = $this->widget('bootstrap.widgets.TbGridView', $options);
?>
        </div>
    </div>
</div>
<?php
Tak::regScript('end', '
    var chlist =  $("#list-grid .check");
    if (typeof opener.datalist!=="undefined"&&opener.datalist.length>0) {    
                chlist.each(function(){
                    if (opener.checkeProduct($(this).val())) {
                        $(this).remove();
                    }
                });     
    }
$(document).on("click","#select_all",function(){
    var t = $(this).prop("checked");
    $("#list-grid .check").each(function(i,el){
        $(el).prop("checked",t);
    });
 }).on("click","#tak-select",function(){
    var list = $("#list-grid .check:checked")
    , length = list.length
    ;
    if (length==0) {
        alert("未有选中产品!");
        return false;
    }
    var data = [];
    for (i=0; i <length ; i++) { 
        data.push(list.eq(i).val());
    }
    dataObj = select(data); 
    closeWin(dataObj);
    // opener.addData();
 })

var select = function(data){
    var qustr = ","+data.join(",")+","; 
    queryResult = Enumerable.from(listData)
    .where(function (x) {
        return qustr.indexOf(","+x.itemid+",")>=0 ;
    })
    .select(function(x){
        return x;
    })
    .toArray();
    return queryResult;
}
, listData = ' . Tak::modulesToJson($m));
?>
    <div class="pop-bottom">
        <button id="tak-select">选择产品</button>
    </div>