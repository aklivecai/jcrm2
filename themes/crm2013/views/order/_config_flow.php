<?php 
	$strHtml = '<li id="flow[:typeid]=:listorder"><i class="icon-fullscreen" title="拖动排序"></i>
	<input type="hidden" value=":listorder" name="flow[:typeid]" />
	<input type="text" class="ini" name="flow[:typeid]" value=":typename">
	<a href=":link" title="点击删除" class="ajax-del"><i class="icon-remove" ></i></a></li>';
	$result = '';
	foreach ($flowTypes as $key => $value) {
		$result .= strtr($strHtml,array(
				':listorder'=>$value->listorder,
				':typeid'=>$value->typeid,
				':typename'=>$value->typename,
				':link' => $this->createUrl('flow',array('act'=>'delete','itemid'=>$value->typeid)),
			));
	}
	echo $result;
?>