
<style type="text/css">
 .wap-tak{ background:#fff;padding:0;  margin:0 auto;width:800px;}
 p{padding:0;margin:0;}
 .box{background:url('<?php echo Yii::app()->request->hostInfo.yii::app()->baseUrl;?>/upload/email/bg_03.jpg') no-repeat; width:650px; height:593px; margin:0 auto; padding:41px 29px 0 29px;font-size:14px; color:#333;}
 .mail_c{border:1px #6a6a6a solid; background:#fff url('<?php echo Yii::app()->request->hostInfo.yii::app()->baseUrl;?>/upload/email/bg_c_10.jpg') repeat-x left bottom; width:650px;}
 .mail_t{ background:url(<?php echo Yii::app()->request->hostInfo.yii::app()->baseUrl;?>/upload/email/top_06.jpg) no-repeat; height:106px;}
 .mail_b{ background:url(<?php echo Yii::app()->request->hostInfo.yii::app()->baseUrl;?>/upload/email/pic_12.jpg) no-repeat right center; line-height:24px;padding:15px 20px 10px 20px;}
 .mail_b p{ padding-bottom:30px;}
 .mail_b label{ color:#d20411; font-size:18px; font-family:"微软雅黑";}
</style>
&nbsp;&nbsp;
<div class="wap-tak">
<div class="box">
	<div class="mail_c">
    	<div class="mail_t">&nbsp;</div>
        <div class="mail_b"><p>尊敬的 <?php echo $model->company ;?><br />           
您好！<br />
具人同行在线营销管理系统后台地址为：<br />
<?php echo $model->getHtmlLink(false) ;?>（请保存）<br />
用户名：admin<br />
密  码：admin<br />
激活账号后可免费试用15天<br />
后台地址唯一，请将系统后台地址保存</p>

<p>如有不清楚请随时与我们联系，我们会有专业的商务顾问免费上门给您演示。欢迎<br />您提出宝贵的意见和建议，这将是我们不断前进的动力！</p>

<p>具人同行在线营销管理系统功能介绍：<br />
<a href="http://www.9juren.com/about/crm.html" target="_blank">http://www.9juren.com/about/crm.html</a><br />
<a href="http://www.9juren.com/1/email2013/index.html" target="_blank">http://www.9juren.com/1/email2013/index.html</a></p>
		</div>
    </div>
</div>
</div>
<input type="r" required="">
&nbsp;&nbsp;