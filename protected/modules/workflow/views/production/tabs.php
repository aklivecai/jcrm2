<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/smart_wizard_vertical.css">
<?php 
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'flowCreate',
    'action'=>$this->createUrl('create'),
    'htmlOptions'=>array('class'=>'well'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'focus'=>array($model,'flow_name'),
)); ?>
<div class="row">
    <span class="right"><a class="btn btn-inverse"href="<?php echo Yii::app()->request->getUrlReferrer(); ?>">返回</a></span>
</div>
<?php
$this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs',
    'placement'=>'left', // 'above', 'right', 'below' or 'left'
    'tabs'=>array(
        array('label'=>'流程信息', 'content'=>$this->renderPartial('_form', array('model'=>$model, 'form'=>$form, 'formList'=>$formList),true), 'active'=>true),                                    
        // array('label'=>'流程信息', 'content'=>$this->renderPartial('_dd', array('form'=>$form, 'model'=>$model, 'd'=>'ddd'),true), 'active'=>true),
        array('label'=>'流程步骤', 'content'=>$this->renderPartial('_tabular', array('form'=>$form, 'model'=>$stepModel), true)),
    ),
));
?>
<!-- Smart Wizard   -->
<!-- <div id="wizard" class="swMain">
    <ul>
            <li>
                <a href="#step-1">
                    <label class="stepNumber">1</label>
                    <span class="stepDesc">
                        第一步<br />
                        <small>流程信息</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-2">
                    <label class="stepNumber">2</label>
                    <span class="stepDesc">
                        Step 2<br />
                        <small>Step 2 description</small>
                    </span>
                </a>
            </li>
    </ul>
    <div id="step-1">
        <?php //echo $this->renderPartial('_form', array('form'=>$form, 'model'=>$model),true); ?>
    </div>
    <div id="step-2">
        <?php //echo $this->renderPartial('_tabular', array('form'=>$form, 'model'=>$stepModel), true); ?>
    </div>
</div> -->
<!-- End SmartWizard Content -->        
<?php $this->endWidget(); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.smartWizard-2.0.min.js'); ?>
<script type="text/javascript">
$(document).ready(function(){
    $('#wizard').smartWizard({
        // selected: 1,
        // errorSteps:[0],
        labelNext: "下一步",
        labelPrevious: "上一步",
        labelFinish: "提交",
        onFinish: submitAction,
        // transitionEffect:"slideleft",
        // onLeaveStep: leaveAStepCallback,
        // onFinish:onFinishCallback,
        // enableFinishButton: true
    });
    
    function submitAction(){
        $('#wizard').smartWizard("setError", "2");
        $('#flowCreate').submit();
    }     
});

</script>