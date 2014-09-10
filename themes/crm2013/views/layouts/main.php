<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--[if lt IE 7]>
    <meta http-equiv="refresh" content="0; url=<?php echo Yii::app()->createUrl('/site/ie6'); ?>" />
    <![endif]-->

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="icon" type="image/ico" href="/favicon.ico"/>
    <!--[if lt IE 10]>
    <link href="<?php echo $this->getAssetsUrl(); ?>css/ie8.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->getAssetsUrl(); ?>js/ie.js?>"></script>
    <![endif]-->
    <?php

Yii::app()->clientScript->registerCoreScript('history');
Yii::app()->bootstrap->register();
$this->regScriptFile('jcrm.min.js?t=201410');


$this->regCssFile(array(
    YII_DEBUG ? 'jcrm.css' : 'jcrm.css?t=201410',
));

$scrpitS = array(
    '_ak/js/modernizr.js',
);
$cssS = array();

$path = '_ak/js/plugins/datepicker/';
// $cssS[] = $path . 'skin/WdatePicker.css';
$scrpitS[] = $path . 'WdatePicker.js';
$scrpitS[] = $path . 'lang/zh-cn.js';

$path = '_ak/js/plugins/select2/';
$cssS[] = $path . 'select2.min.css';
$scrpitS[] = $path . 'select2.min.js';
// $scrpitS[] = $path . 'select2_locale_zh.js';
// 弹窗插件
$path = '_ak/js/plugins/jq.artDialog/';
$cssS[] = $path . 'css/ui-dialog.css';
$scrpitS[] = $path . 'dialog-plus-min.js';

if (YII_DEBUG) {
    $scrpitS[] = '_ak/js/jq.common.js';
}
Tak::regScriptFile($scrpitS, 'static');
Tak::regCssFile($cssS, 'static');

Tak::regScript('crmpatch', 'var CrmPath = "' . Yii::app()->getBaseUrl() . '/";', CClientScript::POS_HEAD);
$targetWin = <<<END
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
Tak::regScript('target-win', $targetWin, CClientScript::POS_END);
?>
    <script type="text/javascript" src="<?php echo $this->getAssetsUrl(); ?>js/lib.js?20140620"></script>
  </head>

  <body id="ibody" class="<?php echo Yii::app()->user->getState('themeSettings_bg'); ?>" >
    <div class="wrapper<?php echo ' ' . Yii::app()->user->getState('themeSettings_style');
if (Yii::app()->user->getState('themeSettings_fixed')) echo ' fixed'; ?>">
      <div class="header">
        <?php
echo CHtml::tag('a', array(
    'class' => 'logo',
    'href' => Yii::app()->homeUrl,
    'title' => CHtml::encode(Yii::app()->name)
) , '<span>' . CHtml::encode(Yii::app()->name) . '</span>');
?>

        <ul class="header_menu">
          <li class="list_icon " <?php if (Yii::app()->user->getState('themeSettings_menu')) echo 'style="display: list-item;"'; ?>><a href="#">&nbsp;</a></li>
          <li class="settings_icon"> <a href="#" class="link_themeSettings">&nbsp;</a>
            <div id="themeSettings" class="popup">
              <div class="head clearfix">
                <div class="arrow"></div>
              <span class="isw-settings"></span> <span class="name">主题设置</span> </div>
              <div class="body settings">
                <div class="row-fluid">
                  <div class="span3"><strong>颜色:</strong></div>
                  <div class="span9">
                    <a class="styleExample active" title="Default style" data-style="">&nbsp;</a>
                    <a class="styleExample silver " title="Silver style" data-style="silver">&nbsp;</a>
                    <a class="styleExample dark " title="Dark style" data-style="dark">&nbsp;</a>
                    <a class="styleExample marble " title="Marble style" data-style="marble">&nbsp;</a>
                    <a class="styleExample red " title="Red style" data-style="red">&nbsp;</a>
                    <a class="styleExample green " title="Green style" data-style="green">&nbsp;</a>
                    <a class="styleExample lime " title="Lime style" data-style="lime">&nbsp;</a>
                    <a class="styleExample purple " title="Purple style" data-style="purple">&nbsp;</a>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span3"><strong>背景:</strong></div>
                  <div class="span9"> <a class="bgExample active" title="Default" data-style="">&nbsp;</a> <a class="bgExample bgCube " title="Cubes" data-style="cube">&nbsp;</a> <a class="bgExample bghLine " title="Horizontal line" data-style="hline">&nbsp;</a> <a class="bgExample bgvLine " title="Vertical line" data-style="vline">&nbsp;</a> <a class="bgExample bgDots " title="Dots" data-style="dots">&nbsp;</a> <a class="bgExample bgCrosshatch " title="Crosshatch" data-style="crosshatch">&nbsp;</a> <a class="bgExample bgbCrosshatch " title="Big crosshatch" data-style="bcrosshatch">&nbsp;</a> <a class="bgExample bgGrid " title="Grid" data-style="grid">&nbsp;</a> </div>
                </div>
                <div class="row-fluid">
                  <div class="span3"><strong>固定布局:</strong></div>
                  <div class="span9">
                    <input type="checkbox" name="settings_fixed" value="1" checked="checked" />
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span3"><strong>隐藏 菜单:</strong></div>
                  <div class="span9">
                    <input type="checkbox" name="settings_menu" value="1"/>
                  </div>
                </div>
              </div>
              <div class="footer">
                <button class="btn link_themeSettings" type="button">关闭</button>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="menu <?php if (Yii::app()->user->getState('themeSettings_menu')) echo 'hidden'; ?>">
        <div class="breadLine">
          <div class="arrow"></div>
          <div class="adminControl active"> 欢迎，
            <?php
echo Tak::getManame();
// echo Tak::getManageid();


?>

          </div>
        </div>
        <div class="admin">
          <div class="image">
            <?php $this->widget('application.components.GoogleQRCode', array(
    'size' => 82,
    'content' => Yii::app()->request->hostInfo . Yii::app()->request->getUrl() ,
    'htmlOptions' => array(
        'class' => 'img-polaroid'
    )
));
?>
          </div>
          <ul class="control">
            <!-- <li><i class="icon-comment"></i> <a href="#<?php echo Yii::app()->createUrl('site/messate'); ?>">消息</a> <a href="<?php echo $this->createUrl('/site/message') ?>" class="caption red">12</a></li> -->
            <li><i class="icon-user"></i><a href="<?php echo $this->createUrl('/site/profile') ?>">个人资料</a></li>

            <li id="tak-changepwd" style="position:relative;"><i class="icon-magnet"></i> <a href="<?php echo $this->createUrl('/site/changepwd') ?>" class="chage-pwd">修改密码</a>
            </li>
            <li><i class="icon-share-alt"></i> <a href="<?php echo $this->createUrl('/site/logout') ?>" class="logout "><span class="red">退出系统</span></a></li>
            <li><i class="icon-share"></i>企业编号:
              <!--
              <span class="label label-warning"><?php echo Tak::getFormid(); ?></span>
              -->
              <span href="messages.html" class="caption"><?php echo Tak::getFormid(); ?></span>

            </li>
          </ul>
          <div class="info"> <span>上一次登录：<?php echo Yii::app()->user->last_login_time; ?></span> </div>
        </div>
        <?php
$items = Tak::getMainMenu();
$this->widget('application.components.MyMenu', array(
    'itemTemplate' => '{menu}',
    'activateParents' => true, //父节点显示
    'itemCssClass' => 'openable',
    'activeCssClass' => 'active',
    'firstItemCssClass' => '', //第一个
    'lastItemCssClass' => '', //最后一个
    'htmlOptions' => array(
        'class' => 'navigation'
    ) ,
    'encodeLabel' => false, //是否过滤HTML代码
    'submenuHtmlOptions' => array() ,
    /*'linkLabelWrapper' => "", //显示内容的标签*/
    'items' => $items
));
?>
        <div class="dr"><span></span></div>

        <div class="widget-fluid">
          <div id="menuDatepicker"></div>
        </div>
        <div class="dr"><span></span></div>

      </div>
      <div class="content <?php if (Yii::app()->user->getState('themeSettings_menu')) echo 'wide'; ?>">

        <!-- breadcrumbs -->
        <div class="breadLine">
          <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links' => $this->breadcrumbs,
)); ?>
          <!-- breadcrumbs -->
        </div>
        <div class="workplace">
          <?php echo $content; ?>
        </div>
      </div>
      <div class="hide">
        <?php
CHtml::tag('iframe', array(
    'src' => Yii::app()->createUrl('/site/appchace') ,
    'style' => 'width:0px; height:0px; visibility:hidden; position:absolute; border:none;'
) , '') ?>
      </div>
    </div>
    <?php
Tak::showMsg();
?>
    <?php Tak::copyright() ?>
    <?php
if (YII_DEBUG && false) {
    
    $str = '
            <ul>
                <li>
                    导入通讯录：1个<a href="/AddressBook/Admin?AddressBook[add_time]=1395019772&AddressBook[add_ip]=1885283417">点击浏览</a>
                </li>
            </ul>
        
';
    echo strlen($str);
    echo "\n";
    
    $str = AK::strip_nr($str);
    echo strlen($str);
    echo "\n";
    
    echo $str;
    
    $cost_id = Tak::fastUuid();
    for ($i = 20;$i > 0;$i--) {
        $cost_id = Tak::numAdd($cost_id, $i);
        echo sprintf("%s\n", $cost_id);
    }
    
    $str = Tak::fastUuid() . '122223333333333333333333333333.x%|898sxs;.$ContactpPrson.*http://hao123.com';
    $str = 'Clienteles.**http://hao123.com||http%3A%2F%2Fhao123.com';
    $str = "abcdefghijklmnopqrstuvwz=.";
    $str = "http://192.168.0.201/GitHub/CRM/manage/RevokeSub/d0bfbcf5lkXO8tBldSAltbUAYOVFtRUFQGAApKQkxSUQoBB1NQVgBVHRNO?name=8a0b3b76RMK645V1JYDgNWVwMCVFAFVwBVVVIdGx1QAloNA1NQAFBVTRoc";
    // $str = "1";
    $s1 = Tak::setCryptKey($str);
    $s2 = Tak::getCryptKey($s1);
    echo strlen($s1);
    echo sprintf("\n\n %s\n\n %s\n\n %s\n\n", $str, $s1, $s2);
    
    $crypt = new SysCrypt();
    $s1 = $crypt->encrypt($str);
    
    $s2 = $crypt->decrypt($s1);
    echo sprintf("%s\n %s\n", $s1, $s2);
    // urldecode
    echo strlen(Yii::app()->request->url);
}
/*
  <!---->
    <!--注意,下面href后没有双引号,若要加入需用%22-->
    <!--
    <a href="about: 点此下载" target="_blank"> 点此打入下载页面 </a>
    <a href="data:text/html, 点此下载" target="_blank"> 点此打入下载页面 </a>
    -->
*/
?>  
  </body>
</html>