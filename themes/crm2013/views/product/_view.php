<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        array(
            'name' => 'typeid',
            'value' => Category::getProductName($model->typeid, ' / ') ,
        ) ,
        array(
            'name' => 'price',
            'value' => Tak::format_price($model->price) ,
        ) ,
        'material',
        'spec',
        'color',
        'unit',
        'note',
        array(
            'name' => 'add_time',
            'value' => Tak::timetodate($model->add_time, 6) ,
        ) ,
        array(
            'name' => 'modified_time',
            'value' => Tak::timetodate($model->modified_time, 6) ,
        ) ,
    ) ,
)); ?>