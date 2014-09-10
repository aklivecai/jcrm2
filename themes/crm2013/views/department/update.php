<?php
/* @var $this ClienteleController */
/* @var $model Clientele */
$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'admin'
    ) ,
    $model->name => array(
        'view',
        'id' => $model->primaryKey
    ) ,
    Tk::g('Update') ,
);
?>	
<?php $this->renderPartial('_form', array(
    'model' => $model
)); ?>