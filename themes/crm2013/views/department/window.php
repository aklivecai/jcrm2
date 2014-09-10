
<?php
$itemid = $this->getSId($id);
$items = array();
if ($this->tabs) {
    foreach ($this->tabs as $key => $value) {
        $_id = $value['itemid'];
        $_item = array(
            'label' => $value['name'],
            'url' => array(
                'window',
                'id' => $this->setSId($_id) ,
                'action' => $action,
                'index' => $index,
            )
        );
        if ($itemid == $_id) {
            $_item['active'] = true;
        }
        $items[] = $_item;
    }
}
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked' => false, // whether this is a stacked menu
    'items' => $items,
));
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'list-form',
        'to-view' => 'list-worker',
    ) ,
    'action' => $this->createUrl($this->route, array(
        'id' => $id,
        'action' => $action,
        'index' => $index,
    )) ,
    'method' => 'get',
));

Tak::regScript('footer', '
    if(window.opener == undefined) {
        window.opener = window.dialogArguments;
    }     
    var setData = function(data){
        window.opener.addDepartments(data,"' . $action . '","' . $_GET['index'] . '");
        window.close(); 
    }
', CClientScript::POS_END);
?>
<div>
<table class="items table table-striped table-bordered table-condensed " style="margin-bottom:0">
    <colgroup align="center">
    <col width="auto">
    <col width="60px"/>
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $data->getAttributeLabel('name') ?></th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php
echo $form->textField($data, 'name', array(
    'size' => 10,
    'maxlength' => 10
));
?>
            </td>
            <td><button type="submit" class="btn btn-small">搜索</button></td>
        </tr>
    </tbody>
</table>
<?php
$this->endWidget();
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $data->search(12) ,
    'ajaxUpdate' => false,
    'id' => 'list-worker',
    'itemView' => sprintf("_window_%s", $action) ,
    'tagName' => 'table',
    'itemsTagName' => 'tbody',
    'viewData' => array(
        'id' => $id
    ) ,
    'htmlOptions' => array(
        'class' => 'table table-bordered table-condensed',
    ) ,
    'template' => '
<colgroup align="center">
    <col width="auto">
    <col width="60px"/>
</colgroup>
    {items}<tfoot><tr><td colspan="2">{pager}</td></tr></tfoot>',
    'emptyText' => '<tr><td colspan="2"> 暂无数据 </td></tr>',
));
?>
</div>