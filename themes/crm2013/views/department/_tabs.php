<?php
if (!isset($action)) {
    $action = 'Create';
}

$itemid = $this->getSId($id);
$items = array(
    'action' => array(
        'label' => Tk::g(array(
            $action,
            'Department'
        )) ,
        'url' => '#'
    )
);
if ($this->tabs) {
    foreach ($this->tabs as $key => $value) {
        $_id = $value['itemid'];
        $_item = array(
            'label' => $value['name'],
            'url' => array(
                'view',
                'id' => $this->setSId($_id)
            )
        );
        if ($itemid == $_id) {
            $_item['active'] = true;
        }
        $items[] = $_item;
    }
    if (isset($itemid) && $itemid > 0) {
        $items['action'] = array(
            'label' => Tk::g(array(
                            'Workshop',
                            'Setting'
                        )),
            'items' => array(
                array(
                    'label' => '添加',
                    'url' => array(
                        'create'
                    ) ,
                    'linkOptions' => array(
                        'class' => 'data-ajax',
                        'title' => Tk::g(array(
                            'Create',
                            $this->modelName
                        ))
                    )
                ) ,
            )
        );
        $items['action']['items'][] = array(
            'label' => '修改',
            'url' => array(
                'update',
                'id' => $id
            ) ,
            'linkOptions' => array(
                'class' => 'data-ajax',
                'title' => Tk::g(array(
                    'Update',
                    $this->modelName
                ))
            )
        );
        $items['action']['items'][] = array(
            'label' => '删除',
            'linkOptions' => array(
                'class' => 'red delete',
                'data-title' => $model->name
            ) ,
            'url' => array(
                'delete',
                'id' => $id
            )
        );
    } else {
        $items['action']['active'] = true;
    }
} else {
    $items['action']['active'] = true;
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked' => false, // whether this is a stacked menu
    'items' => $items,
));
