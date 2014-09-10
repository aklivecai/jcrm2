<table class="tak-table action-fold">
	<caption>订单跟踪</caption>
	<colgroup align="center">
	<col width="140px"/>
	<col width="85px"/>
	<col width="150px"/>
	</colgroup>	
	<thead>
	<tr>
		<th>处理时间</th>
		<th>操作人</th>
		<th>状态</th>
		<th>文件</th>
		<th>备注详情</th>
	</tr>
	</thead>
	<tbody class="wap-products">
	<?php
$list = $model->getFlows();
$result = '';
$strHtml = '<tr>
	<td>:add_time</td>
	<td>:action_user</td>
	<td>:name</td>
	<td>:pics</td>
	<td>:note</td>
	</tr>';
$arr = false;
foreach ($list as $key => $value) {
    $arr = array(
        ':pics' => $value->getFilesImg() ,
        ':add_time' => Tak::timetodate($value->add_time, 6) ,
        ':action_user' => $value->action_user,
        ':name' => $value->getName() ,
        ':note' => $value->note,
    );
    $result.= strtr($strHtml, $arr);
}
echo $result;
?>
	</tbody>	
</table>
<?php if ($model->status == 10): ?>
<?php
    $orderFlow = new OrderFlow();
    $dataSelect = array(
        '1' => '同意',
        '0' => '不同意',
    );
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'order-change-form',
        'action' => $this->createUrl('UpChange', array(
            'id' => $model->primaryKey
        )) ,
        'enableAjaxValidation' => false,
    ));
    
    $iupload = $this->widget('ext.Plupload.PluploadWidget', array(
        'config' => array(
            'pluploadPath' => true,
            'url' => $this->createUrl('/it/upload') ,
            'browse_button' => 'pickfiles',
            'container' => 'container',
        ) ,
        'id' => 'order-upload'
    ) , true);
    
    $postFileUrl = $this->createUrl('/it/upload');
    $tempscript = "
  var objUpload = {
  runtimes : 'flash,html5,html4',
  'url': '$postFileUrl',
  flash_swf_url : '$iupload/Moxie.swf',
  silverlight_xap_url : '$iupload/Moxie.xap', 
  browse_button:'status-pickfiles' ,
  container:'status-container' ,
  filters : {
    max_file_size : '10mb',
    mime_types: [
      {'title' : 'Image files', 'extensions' : 'jpg,gif,png,jpeg'},
      {'title' : 'Zip files', 'extensions' : 'zip,rar'},
      {'title' : 'Doc files', 'extensions' : 'doc,docx,xls,xlsx,rtf,txt'}
    ]
  }}
  , uploader = new plupload.Uploader(objUpload)
  , initUpload = function(_elem){
	var wap = $(_elem)
	, filelist = wap.find('.status-filelist')
	;
	uploader.init();	
	uploader.bind('PostInit', function(up) {
		filelist.html('');
	});
	uploader.bind('FilesAdded', function(up, files) {
		plupload.each(files, function(file) {
			filelist.append('<div id=\"' + file.id + '\"><a data-to=\"'+file.id+'\" href=\"javascript:;\" class=\"remove\"><i class=\"icon-trash\"></i></a>  ' + file.name + ' <b></b></div></div>');
		});
		up.refresh(); 
		uploader.start();
	});
	uploader.bind('UploadProgress', function(up, file) {
		wap.find('#' + file.id + ' b').html(file.percent + '%');
	});
	uploader.bind('Error', function(up, err) {
		filelist.append('<div class=\"red\">提示: ' + err.message +(err.file ? ', 文件: ' + err.file.name :'') +'</div>'
		);
		up.refresh();
	});
    uploader.bind('FileUploaded', function(up, file,msg) {
    	var elem = wap.find('#' + file.id);
         elem.find('b').html('100%');
         log(arguments);
        if (msg&&typeof msg['response']!='undefined') {
        	var obj = $.parseJSON(msg['response']);        	
        	if (obj&&typeof obj['result']!='undefined') {
        	elem.append('<input type=\"hidden\" name=\"files[]\" value=\"'+obj.result+'\"/>');
        	}
        }
    });
    filelist.on('click',' a.remove',function(event) {
    	event.preventDefault();
    	var id = $(this).attr('data-to');
      	uploader.removeFile(uploader.getFile(id));
      	wap.find('#' + id).remove();
    });
  }

  initUpload($('#status-container'));
  ;

";
    Yii::app()->clientScript->registerScript('', $tempscript, CClientScript::POS_READY);
?>		
<div class="dr"><span></span></div>
<table class="tak-table action-fold" id="changeOrder">
	<caption  class="red">订单变更处理</caption>
	<tbody>
		<tr>
			<th><label> 变更</label></th>
			<td>
<?php
    echo $form->dropDownList($orderFlow, 'status', $dataSelect);
?>
			</td>
		</tr>
		<tr>
			<th><label for="OrderFlow_note">备注</label></th>
			<td>
        <?php echo $form->textArea($orderFlow, 'note', array(
        'size' => 35,
        'maxlength' => 64,
    )); ?>
			</td>
		</tr>
		<tr>
			<th><label> 附件</label></th>
			<td>				
<div id="status-container" class="itak-dr">
	<a class="status-pickfiles" id="status-pickfiles" href="javascript:;">[选择文件]</a>
	<div class="dr"><span></span></div>
	<div class="status-filelist"></div>
</div>
			</td>
		</tr>
		<tr>
			<th><label> </label></th>
			<td>
			<button clsss="btn">确认</button>
			</td>
		</tr>
	</tbody>
</table>

<?php $this->endWidget(); ?>
<div class="dr"><span></span></div>
<?php
endif
?>
<?php if ($orderInfo): ?>
<table class="tak-table action-fold">
	<caption>详细信息</caption>
	<tbody>
	<tr>
		<th><?php echo $orderInfo->getAttributeLabel('date_time'); ?>:</th>
		<td><?php echo Tak::timetodate($orderInfo->date_time, 3); ?></td>
		<th><?php echo $orderInfo->getAttributeLabel('packing'); ?>:</th>
		<td><?php echo OrderType::item('packing', $orderInfo->packing); ?></td>
	</tr>
	<tr>
		<th><?php echo $orderInfo->getAttributeLabel('taxes'); ?>:</th>
		<td><?php echo OrderType::item('taxes', $orderInfo->taxes); ?></td>
		<th><?php echo $orderInfo->getAttributeLabel('convey'); ?>:</th>
		<td><?php echo OrderType::item('convey', $orderInfo->convey); ?></td>
	</tr>
	<tr>
		<th><?php echo $orderInfo->getAttributeLabel('pay_type'); ?>:</th>
		<td colspan="3">
<?php
    echo $pay_type['title'];
    echo $orderInfo->getPayInfo($model->total);
?>
		</td>		
	</tr>
	<tr>
		<th><?php echo $orderInfo->getAttributeLabel('detype'); ?>:</th>
		<td colspan="3">
		<?php
    echo OrderType::getStatus('detype', $orderInfo->detype);
    echo $orderInfo->getContactp();
?>
		</td>
	</tr>
	<tr>
		<th><?php echo $orderInfo->getAttributeLabel('note'); ?>:</th>
		<td colspan="3">
		<?php
    echo $orderInfo->note;
?>
		</td>
	</tr>
	</tbody>
</table>

<?php
endif
?>

<table class="tak-table action-fold">
	<caption>商品清单</caption>
	<thead>
		<tr>
			<th width="150">产品名称</th>
			<th>详情</th>
			<th width="120">单价</th>
			<th width="80">数量</th>
			<th width="150">小计</th>
		</tr>
	</thead>
	<tbody class="wap-products">
	<?php
$list = $model->getProducts();
$result = '';
$strHtml = '<tr>
	<td>:name</td>
	<td>
		<dl>
			<dt>$model:</dt><dd>:model &nbsp;</dd>
			<dt>$standard:</dt><dd>:standard &nbsp;</dd>
			<dt>$color:</dt><dd>:color &nbsp;</dd>
			<dt>$unit:</dt><dd>:unit &nbsp;</dd>
		</dl>
		<div class="kclear"></div>
		<div>
		<strong>$note:</strong>
		:note
		</div>
		:pics
	</td>
	<td class="price-strong">￥:price</td>
	<td>:amount</td>
	<td class="price-strong">￥:sum</td>
	</tr>';
$arr = false;
foreach ($list as $key => $value) {
    $arr = array(
        ':pics' => $value->getFilesImg()
    );
    $icount = 0;
    foreach (array(
        'name',
        'amount',
        'price',
        'sum',
        'unit',
        'model',
        'standard',
        'color',
        'note'
    ) as $v1) {
        $arr[':' . $v1] = $value->{$v1};
        if ($icount > 3) {
            $arr['$' . $v1] = $value->getAttributeLabel($v1);
        }
        $icount++;
    }
    $result.= strtr($strHtml, $arr);
}
echo $result;
?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">合计:
			<strong  class="price-strong">￥
			<?php echo $model->total; ?>
			</strong>
			</td>
		</tr>
	</tfoot>
</table>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
    'id' => 'sssModal'
)); ?>
 
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Modal header</h4>
</div> 
<div class="modal-body">
    <p>用户信息........</p>
</div> 
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Close',
    'url' => '#',
    'htmlOptions' => array(
        'data-dismiss' => 'modal'
    ) ,
)); ?>
</div> 
<?php $this->endWidget(); ?>