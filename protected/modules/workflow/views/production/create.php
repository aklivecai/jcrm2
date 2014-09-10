<?php $this->breadcrumbs[] = Tk::g(array(
    'Workflow',
    'Create'
));
echo JHtml::tag('h1', Tk::g(array(
    'Workflow',
    'Create'
)));
$this->renderPartial('action', array(
    'model' => $model
)); ?>
