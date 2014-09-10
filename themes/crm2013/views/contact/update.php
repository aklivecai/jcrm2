<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->breadcrumbs = array(
    Tk::g('Contacts') => array(
        'adminGroup'
    ) ,
    Tk::g('Update') ,
);
?>	
<?php $this->renderPartial('_form', array(
    'model' => $model
)); ?>