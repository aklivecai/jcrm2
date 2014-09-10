<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
$this->regCssFile('cost/style' . (DEBUG ? '' : '.min') . '.css?t=117');
Tak::regScript('', $strScript, CClientScript::POS_HEAD);
Tak::regWebModule('dialog');
$js = <<<END
if (window.opener == undefined) {
    window.opener = top;
}
var dialog = false;
try {
    dialog = top.dialog.get(window);
} catch (e) {};
END;
$js.= 'var CrmPath = "' . Yii::app()->getBaseUrl() . '/";';
Tak::regScript('crmpatch', $js, CClientScript::POS_HEAD);

$js = <<<END
+ function($) {
    'use strict';
    if (dialog) {
        var title = $('h2');
        if (title) {
            dialog.title(title.text());
            title.remove();
        }
        if (dialog.data && dialog.data.itemid) {
            var itemid = dialog.data.itemid,
                iframe = $(window.parent.document).find('iframe[name=' + itemid + ']');
            $('#content').height(iframe.height());
        }
    }
    $('.ibtn-cancel').on('click', function() {
        closeWindow();
    });
    var closeWindow = function() {
        if (dialog) {
            dialog.close();
        } else {
            widnow.close();
        }
    }
}(jQuery);
var printHtml = function(html) {
    var options = {
        width: 800,
        height: 650
    },
        l = (screen.availWidth - 10 - options.width) / 2,
        t = (screen.availHeight - 30 - options.height) / 2,
        pars = ['width=' + options.width, 'height=' + options.height, 'left=' + l, 'top=' + t, 'scrollbars=1', 'resizable=0'],
        htmls = ['&lt;html&gt;&lt;head&gt;&lt;meta charset="utf-8"&gt; &lt;link rel="stylesheet" type="text/css" href="' + CrmPath + 'css/tak-printf.css?t=010"&gt;&lt;/head&gt;&lt;body&gt;', html, '&lt;div class="footer-print"&gt;&lt;button onclick="window.print()"&gt;打印&lt;/button&gt;&lt;button onclick="window.close();"&gt;关闭&lt;/button&gt; &lt;/div&gt;', '&lt;/body&gt;&lt;/html&gt;', '&lt;script&gt;  &lt;/script&gt;'],
        newWin = window.open('about:blank', 'printf', pars.join(','), null);
    html = htmls.join('');
    html = html.replace(/&gt;/ig, '>');
    html = html.replace(/&lt;/ig, '<');
    newWin.document.open();
    newWin.document.write(html);
    newWin.document.close();
}
END;
Tak::regScript('prit', $js, CClientScript::POS_END);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php echo true ? Yii::app()->name : CHtml::encode($this->pageTitle); ?></title>
<link rel="icon" type="image/ico" href="/favicon.ico"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->getBaseUrl(); ?>/css/tak-printf.css?=201406002" media="print">
<base target="_self">
</head>
<body>
<div id="content">
    <?php echo $content; ?>
</div><!-- content -->
</body>
</html>