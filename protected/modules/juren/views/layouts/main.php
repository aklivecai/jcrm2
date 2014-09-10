<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<?php
$scrpitS = array();
$cssS = array();

$path = '_ak/js/plugins/datepicker/';
$cssS[] = $path . 'skin/WdatePicker.css';
$scrpitS[] = $path . 'WdatePicker.js';
$scrpitS[] = $path . 'lang/zh-cn.js';
$scrpitS[] = '_ak/js/zeroclipboard/ZeroClipboard.js';

$scrpitS[] = '_ak/js/plugins/jq.artDialog/dialog-plus-min.js';
$cssS[] = '_ak/js/plugins/jq.artDialog/css/ui-dialog.css';

Tak::regScriptFile($scrpitS, 'static');
Tak::regCssFile($cssS, 'static');
?>

<script type="text/javascript">
  var CrmPath = '<?php echo Yii::app()->homeUrl; ?>';
    if(CrmPath.indexOf('.php')>0){
        CrmPath+='?r=';
    };
</script>
</head>

<body>

<div class="container" id="page">

    <div id="header">
        <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
    </div><!-- header -->

    <div id="mainmenu">
<?php
$menus = array();
if (Tak::isGuest()) {
    $menus[] = array(
        'label' => '登录',
        'url' => array(
            'default/login'
        ) ,
    );
} else {
    if (Tak::getAdmin()) {
        $menus[] = array(
            'label' => '工具',
            'url' => array(
                'tools/index'
            )
        );
        $menus[] = array(
            'label' => Tk::g('Modules') ,
            'url' => array(
                'modules/index'
            )
        );
    }
    $menus[] = array(
        'label' => '主页',
        'url' => array(
            'default/index'
        )
    );
    $menus[] = array(
        'label' => Tk::g('Test Memebers') ,
        'url' => array(
            'testMemeber/admin'
        )
    );
    $menus[] = array(
        'label' => Tk::g('Contact531') ,
        'url' => array(
            'Contact531/admin'
        )
    );
    $menus[] = array(
        'label' => Tk::g('Test Logs') ,
        'url' => array(
            'testLog/admin'
        )
    );
    
    $menus[] = array(
        'label' => '管理中心',
        'url' => array(
            '/site/index'
        )
    );
    $menus[] = array(
        'label' => '修改密码',
        'url' => array(
            'default/changepwd'
        ) ,
        'linkOptions' => array(
            'class' => 'changepwd'
        )
    );
    
    $menus[] = array(
        'label' => '退出 (' . Yii::app()->user->name . ')',
        'url' => array(
            'default/logout'
        ) ,
        'linkOptions' => array(
            'class' => 'logout'
        )
    );
}
$this->widget('zii.widgets.CMenu', array(
    'items' => $menus
)); ?>
    </div><!-- mainmenu -->

    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $this->breadcrumbs,
)); ?><!-- breadcrumbs -->

    <?php echo $content; ?>
    <div id="footer">
    <?php
echo Yii::app()->params['copyright'];
?>
        <br/>
    </div><!-- footer -->

</div><!-- page -->

</body>
</html>