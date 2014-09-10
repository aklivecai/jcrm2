<?php
$this->breadcrumbs = array(
    '错误'
);
if (isset($error) && isset($error['code'])) {
    $this->pageTitle = ' - ' . $error['code'];
} else {
    $this->pageTitle = ' - 错误';
}
$message = Tak::getMsgByErrors($errors);
?>
<div class="alert alert-error">
    <h4>错误提示</h4>
    <?php echo $message; ?>
</div>

<p>
<?php
$url = isset($url) ? $url : Yii::app()->homeUrl;

if (Yii::app()->request->urlReferrer) {
    $_url = Yii::app()->request->urlReferrer;
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'linkurl',
        'label' => '返回上一页',
        'type' => 'danger',
        'htmlOptions' => array(
            'href' => $_url
        )
    ));
    !$url && $url = $_url;
}
$cl = Yii::app()->getClientScript();
$cl->registerMetaTag("3;url=$url", null, 'refresh');
$cl->registerScript('1', "setTimeout(function(){ window.location = \"" . $url . "\";} ,3 * 1000);");
?>
</p>