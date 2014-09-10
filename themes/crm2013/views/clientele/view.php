<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs = array(
    Tk::g('Clienteles') => array(
        'admin'
    ) ,
);
$items = Tak::getViewMenu($model->itemid);
$nps = $model->getNP(true);
if (count($nps) > 0) {
    array_splice($items, count($items) - 2, 0, Tak::getNP($nps));
}

array_splice($items, count($items) - 2, 0, array(
    '---',
    'Delete' => array(
        'label' => '扔进公海',
        'icon' => 'minus-sign',
        'url' => array(
            'toSeas',
            'id' => $model->itemid
        ) ,
        'linkOptions' => array(
            'class' => 'to-seas'
        )
    ) ,
));
?>

<div class="row-fluid">
  <div class="span8">
    <div class="head clearfix">
      <div class="isw-zoom"></div>
      <h1><?php echo Tk::g('Clienteles') ?></h1>
    </div>
    <div class="block">
      <div class="span9">
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'clientele_name',
        // array('name'=>'rating','type'=>'raw', 'value'=>TakType::getStatus('rating',$model->rating),),
        array(
            'name' => 'annual_revenue',
            'type' => 'raw',
            'value' => TakType::getStatus('annual_revenue', $model->annual_revenue) ,
        ) ,
        array(
            'name' => 'industry',
            'type' => 'raw',
            'value' => TakType::getStatus('industry', $model->industry) ,
        ) ,
        array(
            'name' => 'profession',
            'type' => 'raw',
            'value' => TakType::getStatus('profession', $model->profession) ,
        ) ,
        array(
            'name' => 'origin',
            'type' => 'raw',
            'value' => TakType::getStatus('origin', $model->origin) ,
        ) ,
        array(
            'name' => 'employees',
            'type' => 'raw',
            'value' => TakType::getStatus('employees', $model->employees) ,
        ) ,
        'email',
        'address',
        'telephone',
        'fax',
        'web',
        // array('name'=>'display','type'=>'raw', 'value'=>TakType::getStatus('display',$model->display),),
        array(
            'name' => 'last_time',
            'value' => Tak::timetodate($model->last_time, 6) ,
        ) ,
        array(
            'name' => 'add_time',
            'value' => Tak::timetodate($model->add_time, 6) ,
        ) ,
        array(
            'name' => 'modified_time',
            'value' => Tak::timetodate($model->modified_time, 6) ,
        ) ,
        'note',
    ) ,
)); ?>
      </div>
      <div class="span3">
        <?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'list',
    'items' => $items,
));
?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="span4">
<?php
$items = array(
    'ContactpPrson',
    'Contact'
);
foreach ($items as $value) {
?>
    <div class="row-fluid">
      <div class="span12">
        <div class="head clearfix">
          <div class="isw-users"></div>
          <h1><?php echo Tk::g($value) ?></h1>
          <ul class="buttons">
            <li>
            <a href="<?php echo Yii::app()->createUrl("$value/create", array(
        $value . "[clienteleid]" => $model->itemid
    )); ?>" title="<?php echo Tk::g(array(
        'Create',
        $value
    )) ?>"><i class="isw-plus"></i></a>
            </li>
          </ul>
        </div>
        <div class="block-fluid users">
<?php
    $this->widget('ext.AkCListView', array(
        'dataProvider' => $value::model()->recently(4, 'clienteleid=' . $model->itemid) ,
        'template' => '{items}',
        'itemView' => "_$value",
        'emptyText' => '<p>暂无数据</p>',
        'htmlOptions' => array(
            'class' => ''
        ) ,
    ));
?>            

        <div class="footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Tk::g('More') ,
        'url' => array(
            "$value/admin",
            $value . '[clienteleid]' => ($model->itemid)
        ) ,
        'size' => 'small',
    )); ?>
        </div>          
        </div>

    </div>
<?php
} ?>

    </div>
  </div>
  </div>
  <div class="kclear"></div>
  <div class="dr"><span></span></div>
</div>
