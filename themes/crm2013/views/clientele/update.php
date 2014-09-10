<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs = array(
    Tk::g('Clienteles') => array(
        'admin'
    ) ,
    Tk::g('Update') ,
);
?>	
<?php $this->renderPartial('_form', array(
    'model' => $model
)); ?>