<?php
$this->breadcrumbs[] = Tk::g(array(
    'Workflow',
    'Update'
));

echo JHtml::tag('h1', Tk::g(array(
    'Workflow',
    'Update'
)));
$this->renderPartial('action', array(
    'model' => $model
)); ?>