<div class="mangeid">
<?php
!isset($options) && $options = array();
!isset($name) && $name = '';
$options['placeholder'] = '输入公司名字,编号,登陆名字';
$options['class'] = 'key';
echo JHtml::textField('name', $name, $options);
echo ' ';
echo JHtml::button('选择用户', array(
    'class' => "btn_mangeid"
));
echo ' <br />';
echo JHtml::dropDownList($id, $value, array());

Tak::regScript('manageid', "
	var wap = $('.mangeid')
		inputs = wap.find('.key')
	;
	inputs.each(function(i,elem){
		var t = $(elem)
			p = t.parent(),
			btn = p.find('.btn_mangeid')
		;	
		btn.on('click',function(){
			
		})
		if (elem.val()) {
			# code...
		}
	});
	 initSelect = function(key,){
    $.ajax({
       url: '../tools/selectmid'
       success: function(data){
           
       },
   });
	 }
	;", CClientScript::POS_END);
?>
</div>
