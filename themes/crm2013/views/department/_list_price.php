
<?php 

$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'submit-form',
    ) ,
    'action' => $this->createUrl('CreateModel', array(
        'id' => $id,
        'action' => 'price',
    )) ,
    'method' => 'post',
));

?>
<table class="table" summary="工人">
	<colgroup align="center">
	<col width="auto"/>
	<col width="20%"/>
	<col width="68px"/>
	</colgroup>
	<thead>
		<tr>
			<th>工序或产品名</th>
			<th>价格</th>
			<th>操作</th>
		</tr>
	</thead>
		<tbody>
		<tr>
				<td><input type="text" name="m[name]" required="required"/></td>
				<td><input type="number" name="m[price]" required="required"/></td>
				<td><button type="submit" class="btn btn-small">新增</button></td>
		</tr>
		</tbody>
	</table>

<?php
$this->endWidget();

$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'list-form',
        'to-view' => 'list-price',
        'style' => 'margin-bottom:-5px'
    ) ,
    'action' => Yii::app()->createUrl($this->route, array(
        'id' => $id
    )) ,
    'method' => 'get',
));
?>
<table class="table" summary="工人">
	<colgroup align="center">
	<col width="auto"/>
	<col width="20%"/>
	<col width="68px"/>
	</colgroup>
		<tbody>
		<tr>			
			<td>
				<?php
echo $form->textField($priceData, 'name', array(
    'size' => 10,
    'maxlength' => 10
));
?>
			</td>
			<td>
				<?php
echo $form->textField($priceData, 'price', array(
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
    'dataProvider' => $priceData->search() ,
    'itemView' => "_price",
    // 'enableHistory' => true,
    'id' => 'list-price',
    'itemView' => "_price",
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
	<col width="auto"/>
	<col width="20%"/>
	<col width="60px"/>
	</colgroup>
	{items}
		<tfoot><tr><td colspan="3">{pager}</td></tr></tfoot><tbody>
	',
    'emptyText' => '<tr><td colspan="3"> 暂无数据 </td></tr>',
));
?>