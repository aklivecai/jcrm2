<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs = array(
    Tk::g('Test Memebers') => array(
        'admin'
    ) ,
    $user->primaryKey => array(
        'view',
        'id' => $user->primaryKey
    ) ,
    $user->company,
);

$this->menu = array_merge_recursive($this->menu, array(
    array(
        'label' => Tk::g('Update') ,
        'url' => array(
            'update',
            'id' => $user->primaryKey
        )
    ) ,
    array(
        'label' => Tk::g(array(
            'Manage',
        )) ,
        'url' => array(
            'memeber/index',
            'Manage[fromid]' => $user->primaryKey,
            'visible' => Tak::getAdmin() ,
        )
    ) ,
    array(
        'label' => Tk::g('Delete') ,
        'url' => '#',
        'linkOptions' => array(
            'submit' => array(
                'deleteDb',
                'id' => $user->primaryKey
            )
        ) ,
    )
));
?>


<div class="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'form',
    'enableAjaxValidation' => false,
)); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'dns'); ?>
        <?php echo $form->textField($model, 'dns', array(
    'size' => 60,
    'maxlength' => 64
)); ?>
        <?php echo $form->error($model, 'dns'); ?>
    </div>
    <div class="row">
        <?php echo $form->label($model, 'dbname'); ?>
        <?php echo $form->textField($model, 'dbname', array(
    'size' => 25,
    'maxlength' => 60
)); ?>
    <?php echo $form->error($model, 'dbname'); ?>
    </div>          

    <div class="row">
        <?php echo $form->label($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>  
    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->textField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'port'); ?>
        <?php echo $form->numberField($model, 'port'); ?>
        <?php echo $form->error($model, 'port'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Tk::g('Save')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->