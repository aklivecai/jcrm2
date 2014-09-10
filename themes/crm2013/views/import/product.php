<?php
echo YiiBase::getPathOfAlias('webroot');
Tak::KD(Yii::app()->getBaseUrl());
Tak::KD(Yii::app()->params['uploadUser']);
?>
<div class="page-header">
	<h1>货品导入 <small>说明：请先下载模板，然后按模板编辑货品信息并上传。</small></h1>
</div>
<a href="#" id="">【模板下载】</a>
<div class="row-fluid">
	<div class="span12">
		<div class="head clearfix">
			<div class="isw-brush"></div>
		</div>
		<div class="block">
			<div id="progress"></div>
			<form action="import" id="import" method="post" enctype="multipart/form-data">
				<input type="file" name="file" id="file"  />
			</form>
			<form action="product" method="post">
				<table class="table">
					<thead>
						<tr>
							<th>xxx</th>
							<th>xxx</th>
							<th>xxx</th>
							<th>xxx</th>
							<th>xxx</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>xxx</td>
							<td>xxx</td>
							<td>xxx</td>
							<td>xxx</td>
							<td>xxx</td>
						</tr>
					</tbody>
				</table>
				<div class="controls">
					<div class="control">
						<textarea name="textarea" placeholder="Your message..." style="height: 70px; width: 100%;"></textarea>
					</div>
					<button class="btn" id="btn-submit">提交</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#file').on('change',function(){
	var ifmname = 'ifm' + Math.random();
	var ifm = $('<iframe src="about:blank" style="position: absolute;top:-9999;" width="2" height="1" frameborder="0" name="'+ ifmname +'">');
	ifm.appendTo($(document.body));
	// $('#import').attr('target',ifmname);
	$('#import').submit();
	$('#progress').html('<img src="http://linux.zixue.it/images/loading.gif" border="0">');
	ifm.on('load',function(data){
		log(window.frames[ifmname].document.title);
		log(ifm.contents().find('body'));
			$('#progress').html('上传完毕');
		// $(this).remove();
	});
});
</script>