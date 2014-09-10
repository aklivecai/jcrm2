<?php
Yii::app()->bootstrap->register();
$this->regCssFile(array(
    'ak.css?t=20140721',
    'window.css?t=20140723',
))->regScriptFile(array(
    'lib.js',
    'k-load.js'
));

Tak::regWebModule('dialog');

$js = <<<END
if (window.opener == undefined) {
    // window.opener = window.dialogArguments;
    window.opener = top;
}
var _dialog = false;
try {
    _dialog = top.dialog.get(window);    
} catch (e) {};
END;

$js.= 'var CrmPath = "' . Yii::app()->getBaseUrl() . '/";';
Tak::regScript('crmpatch', $js, CClientScript::POS_HEAD);

$js = <<<END
;if (_dialog) {
    var title = $('h1');
    if (title) {
        _dialog.title(title.text());
        title.remove();
    };
    if (_dialog.data && _dialog.data.itemid) {
        var __itemid = _dialog.data.itemid,
            iframe = $(window.parent.document).find('iframe[name=' + __itemid + ']');
        $('#content').height(iframe.height());
    };
    var error = $('.errorSummary');
    if(error.length>0){
        error.remove();
        msgShow(error.html());
    };
    // $('<a href="javascript:window.location.href=window.location.href;" class="btn btn-tootls">...</a>').appendTo(document.body)
};
var closeWin = function(parms) {
    if (_dialog) {
        _dialog.close(parms);
        _dialog.remove();
    } else {
        window.close();
    }
};
$('.btn-more-serch').on('click', function() {
    $(this).next('#list-more-search').toggleClass('hide');
});
$(document).on('click','.target-win', function(event) {
    event.preventDefault();
    var url = $(this).attr('href'),
        width=$(this).attr('data-width')?$(this).attr('data-width'):1100,
        height = $(this).attr('data-height')?$(this).attr('data-height'):500
    ;
    url += url.indexOf('?') > 0 ? '&' : '?';
    url += '__x=' + Math.random();
    ShowModal.call($(this),url,{width:width,height:height});
});
END;
Tak::regScript('form-search', $js, CClientScript::POS_END);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>
        <?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="icon" type="image/ico" href="favicon.ico" />
    <!--[if lt IE 9]>
<link href="<?php echo $this->getAssetsUrl(); ?>css/ie8.css?t=20140721" rel="stylesheet" type="text/css" />
<![endif]-->
    <!--[if lt IE 10]> 
<script type="text/javascript" src="<?php echo $this->getAssetsUrl(); ?>js/ie.js?t=20140721>"></script>
<![endif]-->
    <base target="_self">
</head>
<body id="ibody">
    <div id="content" class="content">
        <?php echo $content; ?>
    </div>
    <!-- content -->
</body>
</html>
