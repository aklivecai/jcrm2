<?php 
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'submit-form',
    ) ,
    'action' => $this->createUrl('CreateModel', array(
        'id' => $id,
        'action' => 'worker',
    )) ,
    'method' => 'post',
));
?>
<table class="table ">
	<colgroup align="center">
	<col width="auto">
	<col width="65px"/>
	</colgroup>
	<thead>
		<tr>
			<th>工人名字</th>
			<th>操作</th>
		</tr>
	</thead>

<tbody>
                <tr data-type="worker">
                        <td><input type="text" name="m[name]" required="required"/></td>
                        <td>
                        <button type="submit" class="btn btn-small btn-save">新增</button>
                        </td>
                </tr>
</tbody>    
</table>
<?php
$this->endWidget();
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'list-form',
        'to-view' => 'list-worker',
    ) ,
    'action' => $this->createUrl($this->route, array(
        'id' => $id
    )) ,
    'method' => 'get',
));

?>
<table class="table ">
    <colgroup align="center">
    <col width="auto">
    <col width="65px"/>
    </colgroup>
    <tbody>
        <tr>
            <td>
                <?php
echo $form->textField($workerData, 'name', array(
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
    'dataProvider' => $workerData->search() ,
    // 'enableHistory'=>true,
    'id' => 'list-worker',
    'itemView' => "_worker",
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
	{items}
<tfoot>
	<tr>
			<td colspan="2">{pager}</td>
	</tr>
</tfoot>',
    'emptyText' => '<tr><td colspan="2"> 暂无数据 </td></tr>',
));

?>