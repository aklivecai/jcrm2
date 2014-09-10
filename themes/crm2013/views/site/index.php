<?php
$this->pageTitle = Yii::app()->name;
?>

<div class="row-fluid">
  <div class="span4">
    <?php $this->widget('MyTopData', array(
    'title' => Tk::g('Clientele') ,
    'tags' => Clientele::model()->getIData() ,
    'htmlOptions' => array(
        'class' => 'red'
    )
)); ?>
  </div>

  <div class="span4">
    <?php $this->widget('MyTopData', array(
    'title' => Tk::g('ContactpPrson') ,
    'tags' => ContactpPrson::model()->getIData() ,
    'htmlOptions' => array(
        'class' => 'green'
    )
)); ?>

  </div>
  <div class="span4">
    <?php $this->widget('MyTopData', array(
    'title' => Tk::g('Contact') ,
    'tags' => Contact::model()->getIData() ,
    'htmlOptions' => array(
        'class' => 'blue'
    )
)); ?>

  </div>
</div>
<div class="dr"><span></span></div>
<div class="row-fluid">
  <div class="span4">
    <div class="head clearfix">
      <span class="glyphicon glyphicon-ok-sign"></span>
      <h1><?php echo Tk::g('Clientele'); ?></h1>
      <ul class="buttons">
        <li><a href="<?php echo Yii::app()->createUrl('clientele/create'); ?>" title="<?php echo Tk::g(array(
    'Create',
    'Clientele'
)); ?>"><i class="isw-plus"></i></a></li>
        <li> <a href="#" class="isw-settings"></a>
          <?php
$this->widget('application.components.MyMenu', array(
    'htmlOptions' => array(
        'class' => 'dd-list'
    ) ,
    'items' => array(
        
        array(
            'icon' => 'isw-list',
            'url' => array(
                'clientele/admin'
            ) ,
            'label' => '全部',
        ) ,
        array(
            'icon' => 'isw-target',
            'url' => array(
                'clientele/admin',
                'Clientele_sort' => 'last_time.desc'
            ) ,
            'label' => '最近联系',
        ) ,
        array(
            'icon' => 'isw-text_document',
            'url' => array(
                'clientele/admin',
                'Clientele[industry]' => '1'
            ) ,
            'label' => '新客户',
        )
    ) ,
));
?>
        </li>
      </ul>
    </div>
    <div class="block-fluid accordion">
      <?php
$items = array(
    1,
    2,
    5
);
$mclientele = Clientele::model();
$pre_html = '<table cellpadding="0" cellspacing="0" width="100%" class="sOrders"> <thead> <tr> <th width="80">来源</th> <th>名字</th> <th width="68">日期</th> </tr> </thead> <tbody>';

foreach ($items as $value) {
?>
      <h3><?php echo TakType::item('industry', $value); ?></h3>
      <div>
        <?php
    $post_html = '</tbody> <tfoot> <tr> <td colspan="3" align="right"> ' . $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Tk::g('More') ,
        'url' => array(
            'clientele/admin',
            'Clientele[industry]' => $value
        ) ,
        'size' => 'small',
    ) , true) . '</td> </tr> </tfoot> </table>';
    $this->widget('ext.AkCListView', array(
        'dataProvider' => $mclientele->sort_time()->recently(5, "industry=$value") ,
        'itemView' => '_clientele',
        'template' => '{items}',
        'preItemsTag' => $pre_html,
        'postItemsTag' => $post_html,
        'emptyText' => '<p>暂无数据</p>',
        'htmlOptions' => array(
            'class' => ''
        ) ,
        'viewData' => array(
            'tak' => 500
        )
    )); ?>
  </div>
  <?php
}
?>
</div>
</div>

<div class="span4">
<div class="head clearfix">
  <div class="isw-edit"></div>
  <h1>最近联系客户</h1>
  <ul class="buttons">
    <li><a href="<?php echo Yii::app()->createUrl('contactpPrson/create'); ?>" title="<?php echo Tk::g(array(
    'Create',
    'ContactpPrson'
)); ?>"><i class="isw-plus"></i></a></li>
    <li><a href="<?php echo Yii::app()->createUrl('contactpPrson/admin'); ?>" title="全部"><I class="isw-list"></I> </a></li>
  </ul>
</div>
<div class="block news scrollBox">
  <div class="scroll" style="height: 320px;">
    <?php
$this->widget('ext.AkCListView', array(
    'dataProvider' => contactpPrson::model()->sort_time()->recently(10) ,
    'template' => '{items}',
    'itemView' => '_contactpPrson',
    'emptyText' => '<p>暂无数据</p>',
    'htmlOptions' => array(
        'class' => ''
    ) ,
));
?>
  </div>
</div>
</div>
<div class="span4">
<div class="head clearfix">
  <div class="isw-cloud"></div>
  <h1>联系记录</h1>
  <ul class="buttons">
    <li><a href="<?php echo Yii::app()->createUrl('contact/create'); ?>" title="<?php echo Tk::g(array(
    'Create',
    'Contact'
)); ?>"><i class="isw-plus"></i></a></li>
    <li> <a href="#" class="isw-settings"></a>
      <ul class="dd-list">
        <li><a href="<?php echo Yii::app()->createUrl('contact/create'); ?>"><span class="isw-list"></span> 全部</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('contact/admin', array(
    'Contact[stage]' => 0
)); ?>"><span class="isw-text_document"></span>初期沟通</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('contact/admin', array(
    'Contact[stage]' => 3
)); ?>"><span class="isw-text_document"></span> 方案定制</a></li>
      </ul>
    </li>
  </ul>
</div>
<div class="block users scrollBox">
  <div class="scroll" style="height: 320px;">
    <?php
$this->widget('ext.AkCListView', array(
    'dataProvider' => Contact::model()->sort_time()->recently(10) ,
    'itemView' => '_contact',
    'template' => '{items}',
    'emptyText' => '<p>暂无数据</p>',
    'htmlOptions' => array(
        'class' => ''
    ) ,
));
?>

  </div>
</div>
</div>
</div>
<div class="dr"><span></span></div>
<div class="row-fluid">
<div class="head clearfix">
<div class="isw-calendar"></div>
<h1><?php echo Tk::g('Events'); ?></h1>
</div>
<div class="block-fluid">
<div id="calendar" class="fc"></div>
</div>
</div>
<div class="dr"><span></span></div>