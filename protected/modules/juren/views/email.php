<?php $csspat = Yii::app()->request->hostInfo . yii::app()->baseUrl . '/upload/email/';
?>
<style type="text/css">
 .wap-tak{ background:#fff;padding:0;  margin:0 auto;width:800px;}
 p{padding:0;margin:0;}
 .box{background:url('<?php echo $csspat ?>bg_03.jpg') no-repeat; width:650px; height:593px; margin:0 auto; padding:41px 29px 0 29px;font-size:14px; color:#333;}
 .mail_c{border:1px #6a6a6a solid; background:#fff url('<?php echo $csspat ?>bg_c_10.jpg') repeat-x left bottom; width:650px;}
 .mail_t{ background:url('<?php echo $csspat ?>top_06.jpg') no-repeat; height:106px;}
 .mail_b{ background:url('<?php echo $csspat ?>pic_12.jpg') no-repeat right center; line-height:24px;padding:15px 20px 10px 20px;}
 .mail_b p{ padding-bottom:30px;}
 .mail_b label{ color:#d20411; font-size:18px; font-family:"微软雅黑";}
</style>
&nbsp;&nbsp;
<div class="wap-tak">
<div class="box">
	<div class="mail_c">
    	<div class="mail_t">&nbsp;</div>
        <div class="mail_b"><p>尊敬的 <?php echo $model->company; ?><br />           
您好！<br />
具人同行在线营销管理系统后台地址为：<br />
<?php echo $model->getHtmlLink(false); ?>（请保存）<br />
用户名：admin<br />
密  码：admin<br />
激活账号后可免费试用15天<br />
后台地址唯一，请将系统后台地址保存</p>

<p>具人同行<a href="http://www.9juren.com">www.9juren.com</a>有13年家具行业从业经验，经过近3年的平台搭建，研发了这套家具企业在线营销管理系统，系统里面有两大核心功能，它是网站的升级，软件的延伸。您可以下载附件做个详细的了解。
<br />
具人同行在线营销管理系统功能介绍：
<a href="http://www.9juren.com/crm/">http://www.9juren.com/crm/</a>
</p>
<a href="http://i.9juren.com/_ak/file/9juren2014.pdf" title="立即下载" target="_blank">
	<img src="<?php echo $csspat ?>btn_dow.jpg" alt="立即下载">
</a>
		</div>
    </div>
</div>
</div>

&nbsp;&nbsp;