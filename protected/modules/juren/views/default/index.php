<?php
/* @var $this DefaultController */
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'list-grid',
    'dataProvider' => Test9Memeber::model()->recently(12, 'active_time>0', 'active_time DESC') ,
    'template' => '<h2>最近激活 ' . Tk::g('Test Memebers') . '</h2>{items}',
    'enableSorting' => false,
    'columns' => array(
        array(
            'class' => 'CButtonColumn',
            'template' => '{view} ,{viewlog} ',
            'buttons' => array(
                'viewlog' => array(
                    'label' => '浏览日志',
                    'url' => 'Yii::app()->createUrl("juren/testLog/admin", array("TestLog[fromid]"=>$data->itemid))',
                    'linkOptions' => array(
                        'style' => 'width: 50px'
                    ) ,
                ) ,
            ) ,
        ) ,
        array(
            'name' => 'itemid',
            'headerHtmlOptions' => array(
                'style' => 'width:80px;'
            ) ,
        ) ,
        array(
            'name' => 'company',
            'headerHtmlOptions' => array(
                'style' => 'width:150px;'
            ) ,
        ) ,
        array(
            'name' => 'email',
            'headerHtmlOptions' => array(
                'style' => 'width:80px;'
            ) ,
        ) ,
        array(
            'name' => false,
            'header' => '连接地址',
            'filter' => false,
            'type' => 'raw',
            'value' => '$data->getHtmlLink()',
        ) ,
        array(
            'name' => 'start_time',
            'value' => 'Tak::timetodate($data->start_time,5)',
            'headerHtmlOptions' => array(
                'style' => 'width:120px;'
            ) ,
            'filter' => false
        ) ,
    ) ,
)); ?>

<hr />

<?php
$m = new TestLog();
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'list-grid1',
    'dataProvider' => $m->search() ,
    'template' => '<h2>' . Tk::g('Test Logs') . '</h2>{items}',
    'enableSorting' => false,
    'columns' => array(
        array(
            'class' => 'CButtonColumn',
            'template' => '{viewlog}',
            'buttons' => array(
                'viewlog' => array(
                    'label' => '日志',
                    'url' => 'Yii::app()->createUrl("juren/testLog/view", array("id"=>$data->itemid))',
                    'linkOptions' => array(
                        'style' => 'width: 50px'
                    ) ,
                ) ,
            ) ,
        ) ,
        array(
            'name' => 'fromid',
            'type' => 'raw',
            'value' => 'CHtml::link($data->fromid,Yii::app()->createUrl("juren/testMemeber/view",array("id"=>$data->fromid)))'
        ) ,
        'user_name',
        array(
            'type' => 'raw',
            'name' => 'info'
        ) ,
        array(
            'name' => 'ip',
            'value' => 'Tak::Num2IP($data->ip)',
            'headerHtmlOptions' => array(
                'style' => 'width:85px;'
            )
        ) ,
        array(
            'name' => 'add_time',
            'value' => 'Tak::timetodate($data->add_time,5)',
            'headerHtmlOptions' => array(
                'style' => 'width:120px;'
            )
        ) ,
    ) ,
)); ?>
