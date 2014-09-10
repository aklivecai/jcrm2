<?php
$title = Tk::g(array(
    $model->getModel() ,
    'Import'
));
$this->breadcrumbs = array(
    $title => array(
        $model->getModel()
    )
);
$file = '/k/GitHub/CRM/upload/users/JU1/temp/import/20140315-145106__clientele.xls';
// $model->varfile = $file;
// $model->getTags();


?>
<div class="page-header">
	<h1><?php echo $title ?> <small>请先下载模板，然后按模板编辑信息并上传。</small></h1>
</div>
<h4>
<a href="<?php echo $url ?>">【模板下载】</a>
</h4>
<div class="row-fluid">
	<div class="span12">
		<div class="head clearfix">
			<div class="isw-brush"></div>
		</div>
		<div class="block clearfix">
			<form action="Upload?action=<?php echo $action ?>" id="upload" method="post" enctype="multipart/form-data">
				<input type="file" name="file" class="ifile" />
			</form>
			<div id="message"></div>
			<div id="progress">
<?php
$html = Tak::getUCache($action);
if ($html && is_array($html) && is_array($html['data']) && count($html['data']) > 0) {
    echo sprintf('<span class="label ">%s上传没有导入</span>', Tak::timetodate($html['time'], 6));
    $header = $model->getHeader();
    $this->renderPartial('view', array(
        'header' => $header,
        'data' => $html['data'],
        'action' => $action,
    ));
}
?>				

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var getIfm = function(progress){
	var ifmname = 'ifm' + Math.random()
	, loading = $('<div class="data-loading">....</div> ')
	, ifm = $('<iframe src="about:blank" style="position: absolute;top:-9999;" width="2" height="1" frameborder="0" name="'+ ifmname +'">');
	ifm.appendTo($(document.body));
	progress.addClass("wap-loading");
	loading.appendTo(progress);		
	return ifm;
}
$(document).on('change','.ifile',function(){
	var progress = $('#progress')
	, ifm = getIfm(progress)
	, ifmname = ifm.attr('name')
	, fuplado = $('#upload')
	;
	fuplado.attr('target',ifmname).submit();
	ifm.on('load',function(){
		progress.find('.data-loading').remove();
		progress.removeClass("wap-loading");
		progress.html(ifm.contents().find('#content').html());	
		// ifm.remove();
		 initSelect(progress);
		 fuplado.html('<input type="file" name="file" class="ifile" />');
		 fuplado.find('.ifile').uniform();
	});
});
$(document).on('click','#btn-submit',function(event){
	// return true;
	event.preventDefault();
	// if (sCF("是否确认导入数据？")) {return false;}
	var progress = $('#progress')
	, ifm = getIfm(progress)
	, ifmname = ifm.attr('name')
	, fimport = $('#import')
	;
	fimport.attr('target',ifmname);
	fimport.submit();
	fimport.find('.error').removeClass('error');
	ifm.on('load',function(){
		progress.find('.data-loading').remove();
		progress.removeClass("wap-loading");
		// progress.html(ifm.contents().find('#content').html());
		// ifm.remove();
	});
	return false;
});
window.showError = function(data){
	if (data.length>0) {		
		// parent.gotoElem($('#'+data[0]).eq(0));
		// var ids ='input[name="'+data.join('"],input[name="')
		 var ids ='#'+data.join(',#')
		var list = $(ids).addClass('error');
		parent.gotoElem(list.eq(0));
		list.eq(0).focus();
	};	
}
window.showOk = function(){
	window.location.href = createUrl('Import/message',['action=<?php echo $action ?>']);
}
</script>