<?php
/* @var $this TestLogController */
/* @var $model TestLog */

$this->breadcrumbs = array(
    Tk::g('Test Logs') => array(
        'index',
        'Manage[fromid]' => $model->fromid
    ) ,
    $model->primaryKey,
);
?>

<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'test-memeber-form',
    'enableAjaxValidation' => false,
    'action' => array(
        'update',
        'id' => $model->primaryKey,
    ) ,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model, 'user_pass'); ?>
		<?php echo $form->textField($model, 'user_pass', array(
    'size' => 60,
    'maxlength' => 60
)); ?>
		<?php echo $form->error($model, 'user_pass'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton(Tk::g('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'fromid',
        'user_name',
        array(
            'name' => 'add_ip',
            'value' => Tak::Num2IP($model->add_ip) ,
        ) ,
        array(
            'name' => 'add_time',
            'value' => Tak::timetodate($model->add_time, 6) ,
        ) ,
        array(
            'name' => 'active_time',
            'value' => Tak::timetodate($model->active_time, 6) ,
        ) ,
    ) ,
)); ?>
