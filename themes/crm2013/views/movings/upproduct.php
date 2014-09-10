<?php
Tak::regScriptFile(array(
    'knockout.js',
    'knockout.tak.js',
) , 'static', '_ak/js/advanced');
$this->regScriptFile('load-upproduct.js', CClientScript::POS_END);

$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    Tak::timetodate($model->time) => array(
        'view',
        'id' => $id
    ) ,
    '修改产品明细',
);
$products = $model->getProducts();

Tak::regScript('upproduct', '
  var tags =  ' . CJSON::encode($products) . '
  , id = "' . $id . '"
  , saveUrl = "' . $this->createUrl('saveProdcut', array(
    'id' => $id
)) . '"
  , deleteUrl = "' . $this->createUrl('delProdcut', array(
    'id' => $id
)) . '"
  ;', CClientScript::POS_END);
?>

<?php
$form = $this->beginWidget('CActiveForm', array());
echo $form->hiddenField($model, 'warehouse_id');
?>
<?php $this->endWidget(); ?>
<div class="block-fluid">    
<div>
<?php echo JHtml::link('返回单据', array(
    'view',
    'id' => $id
) , array(
    'class' => 'btn'
)); ?>
<button class="btn" type="button" id="addproduct">添加</button>
</div>
<div class="dr"><span></span></div>
          <div class="min-table">
      <table cellpadding="0" cellspacing="0" width="100%" class="table"  id="table-upproduct">
      <colgroup align="center">      
      <col width="25px" align="center" />
      <col width="auto" span="5" />
      <col span="2" width="95px"/>
      <col width="100px" />      
      <col width="78px"/>
      </colgroup>      
      <caption>
      </caption>
          <thead>
            <tr>
              <th>ID</th>
              <th>产品</th>
              <th>规格</th>
              <th>材料</th>
              <th>颜色</th>
              <th>备注</th>
              <th>单价</th>
              <th>数量</th> 
              <th>合计</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody data-bind=" template:{name:templateToUse, foreach: pagedList }">
          </tbody>
          <tbody>
            <tr>
      <td colspan="8">
      <div class="pagination hide" data-bind="css: { hide: (allPages().length<=1) },attr:{d:allPages().length}" style="margin: 0">  
    <ul><li data-bind="css: { disabled: pageIndex() === 0 }"><a data-bind="click: previousPage">上一页</a></li></ul>
    <ul data-bind="foreach: allPages">
        <li data-bind="css: { active: $data.pageNumber === ($root.pageIndex() + 1) }"><a  data-bind="text: $data.pageNumber, click: function() { $root.moveToPage($data.pageNumber-1); }"></a></li>
    </ul>
    <ul><li data-bind="css: { disabled: pageIndex() === maxPageIndex() }"><a  data-bind="click: nextPage">下一页</a></li></ul>
</div>
          
              </td>
              <td colspan="2">￥<strong data-bind="text: totals" class="text-show"></strong></td>
            </tr>
          </tbody>
          <tbody data-bind="css:{hide:editlist().length==0}" class="hide">
            <tr  id="edits-name">
              <th colspan="10">录入产品</th>
            </tr>
          </tbody>
            <tbody data-bind=" template:{name:'editTmpl', foreach: editlist }"></tbody>
          <tbody data-bind="css:{hide:editlist().length==0}" class="hide">
            <tr>
              <th colspan="10">录入产品</th>
            </tr>
          </tbody>
        </table>
        </div>
        </div>


<script id="itemsTmpl" type="text/html"> 
<tr>
<td data-bind="text: $root.indexNumber($index())"></td>
<td data-bind="text: obj.name"></td>
<td data-bind="text: obj.spec"></td>
<td data-bind="text: obj.material"></td>
<td data-bind="text: obj.color"></td>
<td data-bind="text: note"></td>
<td data-bind="text: price"></td>
<td data-bind="text: number"></td>
<td>￥<span data-bind="text: totals"></span></td>
        <td class="buttons">
            <a class="btn btn-mini" data-bind="click: $root.edit" href="#" title="edit"><i class="icon-edit"></i></a>
            <a class="btn btn-mini" data-bind="click: $root.remove" href="#" title="remove"><i class="icon-remove"></i></a>
        </td>
    </tr>
</script>

 <script id="editTmpl" type="text/html">
   <tr data-bind="attr:{id:eid}">
      <td data-bind="text: $root.indexNumber($index())"></td>
      <td data-bind="text: obj.name"></td>
      <td data-bind="text: obj.spec"></td>
      <td data-bind="text: obj.material"></td>
      <td data-bind="text: obj.color"></td>
      <td><input data-bind="value: note" class="stor-txt" type="text"/></td>
       <td>
        <input data-bind="value: price" required  step="1" type="number" min="0" class="stor-txt"/>
       </td>
       <td><input data-bind="value: number" required step="1" type="number" min="0" class="stor-txt"/></td>
       <td>￥<strong data-bind="text: totals" class="text-show"></strong></td>
        <td class="buttons">
            <a class="btn btn-mini btn-success" data-bind="click: $root.save" href="#" title="保存"><i class="icon-ok"></i></a>
            <a class="btn btn-mini" data-bind="click: $root.cancel" href="#" title="取消"><i class="icon-trash"></i></a>
        </td>
   </tr>
</script>  