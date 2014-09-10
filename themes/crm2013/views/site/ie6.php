<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <title> 您正在使用 Internet Explorer <?php echo $v?> 浏览网页，如果您升级到 Internet Explorer 8 或转换到另一个浏览器，本站将能为您提供更好的服务。</title>
        
<!--[if !IE]>
    <meta http-equiv="refresh" content="0; url=<?php echo Yii::app()->createUrl('/site/index'); ?>" />
<![endif]-->
<!--[if gte IE 7]>
    <meta http-equiv="refresh" content="0; url=<?php echo Yii::app()->createUrl('/site/index'); ?>" />
<![endif]-->
        <meta name="description" content=" 您正在使用 Internet Explorer <?php echo $v?>  浏览网页，如果您升级到 Internet Explorer 8 或转换到另一个浏览器，本站将能为您提供更好的服务。">
        <meta name="keywords" content=" 您正在使用 Internet Explorer <?php echo $v?>  浏览网页，如果您升级到 Internet Explorer 8 或转换到另一个浏览器，本站将能为您提供更好的服务。">
        <style type="text/css">
        body{
        background-color:#2A2A2A;color:#CCC;
        }
        #ie6nomore,#ie6-tips,#ie6-dl,#ie6-tips p{float:left}
        #ie6nomore{width:100%;height:56px;font-size:12px;font-family:Verdana, Geneva, sans-serif}
        #ie6nomore a{text-decoration:none;color:#A8C779}
        #ie6-content{width:700px;margin:0 auto}
        #ie6-tips{width:250px}
        #ie6-tips p{line-height:150%;text-align:left;height:28px}
        #ie6{margin:8px 0 -8px}
        #up{font-weight:700;width:300px}
        #ie6-dl{width:400px;margin-top:18px}
        #ie6-dl span{margin-left:10px}
        #ff{background:url(<?php echo $this->getAssetsUrl(); ?>tak/ie6/ff.gif)}
        #ie8{background:url(<?php echo $this->getAssetsUrl(); ?>tak/ie6/ie8.gif)}
        #sa{background:url(<?php echo $this->getAssetsUrl(); ?>tak/ie6/sa.gif)}
        #ch{background:url(<?php echo $this->getAssetsUrl(); ?>tak/ie6/ch.gif)}
        #op{background:url(<?php echo $this->getAssetsUrl(); ?>tak/ie6/op.gif)}
        #ff,#ie8,#sa,#ch,#op{background-repeat:no-repeat}
        #ff a,#ie8 a,#sa a,#ch a,#op a,{margin-left:20px}
        #ie6nomore{
            height:500px;
        }
        .hlep{
            position: absolute;
            top: 125px;
            padding: 25px 8px;
            background-color: #fff;
            border:2px solid #0d0d0d;
            color:#000;
            font-size: 15px;
            width:250px;
            height:20px;
            text-align: center;
        }
        </style>
    </head>
    <body>

        <div id="ie6nomore">
            <div id="ie6-content">
                <div id="ie6-tips">
                    <p id="ie6">请注意：您正在使用 IE <?php echo $v?>  浏览器</p>
                    <p id="up">如果您升级到 Internet Explorer 8 或转换到另一个浏览器，本站将能为您提供更好的服务</p>
                </div>
                <div id="ie6-dl">
                    <span id="ie8"><a rel="nofollow" href='http://www.browserforthebetter.com/download.html' title='下载 Internet Explorer 8' target='_blank'>IE 8</a></span>
                    <span id="ff"><a rel="nofollow" href='http://www.firefox.com' title='下载 Firefox' target='_blank'>Firefox</a></span>
                    <span id="ch"><a rel="nofollow" href='http://www.google.com/chrome' title='下载 Google Chrome' target='_blank'>Chrome</a></span>
                    <span id="op"><a rel="nofollow" href='http://www.operachina.com/' title='下载 Opera' target='_blank'>Opera</a></span>
                    <span id="sa"><a rel="nofollow" href='http://www.apple.com/safari/download/' title='下载 Safari' target='_blank'>Safari</a></span>
                </div>

                <div class="hlep">
                    <?php
                    echo Yii::app()->params['help'];
                    ?>
                </div>
            </body>
        </html>