<?php
?>
<?php echo CHtml::link(Tk::g('Search') , '#', array(
    'class' => 'search-button'
)); ?>
<div class="search-form">
<?php $this->renderPartial('_search', array(
    'model' => $model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'list-grid',
    'dataProvider' => $model->search() ,
    'ajaxUpdate' => false,
    'enableHistory' => false,
    'columns' => array(
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'header' => CHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
                'onchange' => "$.fn.yiiGridView.update('list-grid',{data:{setPageSize: $(this).val()}})",
            ))
        ) ,
        array(
            'name' => 'fromid',
            'type' => 'raw',
        ) ,
        'user_name',
        array(
            'name' => 'add_ip',
            'value' => 'Tak::Num2IP($data->add_ip)',
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