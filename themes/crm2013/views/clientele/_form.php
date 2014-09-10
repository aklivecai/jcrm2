<?php
$action = $model->isNewRecord ? 'Create' : 'Update';
$items = Tak::getEditMenu($model->itemid, $model->isNewRecord);
?>
<div class="row-fluid">
	<div class="span12">

		<!--
			客户端校验，按下提交以后，设置按钮为注销状态
			防止表单重复提交
			导致多次数据插入数据库
		-->
		<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'clientele-form',
		'type' => 'horizontal',
		'enableAjaxValidation' => false,
		'enableClientValidation'=>true,

		'clientOptions'=>array('validateOnSubmit'=>'true','errorCss'=>'red',),
		)); ?>
		<!-- 'afterValidateAttribute'=>'dform', -->
		<script type="text/javascript">
			var dform =function() {
				log(arguments);
				alert(1);
			};
		</script>
		<?php echo $form->errorSummary($model); ?>

		<div class="head clearfix">
			<i class="isw-documents"></i> <h1><?php echo Tk::g(array(
			'Clientele',
			$action
			)); ?></h1>
			<?php
			$this->widget('application.components.MyMenu', array(
			'htmlOptions' => array(
			'class' => 'buttons'
			) ,
			'items' => $items,
			));
			?>
		</div>
		<div class="block-fluid">
			<div class="row-form clearfix" style="border-top-width: 0px;">
				<?php echo $form->textFieldRow($model, 'clientele_name', array(
				'size' => 60,
				'maxlength' => 100
				)); ?>
			</div>
			<!--
			<div class="row-form clearfix">
				<?php $form->dropDownListRow($model, 'rating', TakType::items('rating')); ?>
			</div>
			-->
			<div class="row-form clearfix">
				<?php echo $form->dropDownListRow($model, 'annual_revenue', TakType::items('annual_revenue')); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->dropDownListRow($model, 'industry', TakType::items('industry')); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->dropDownListRow($model, 'profession', TakType::items('profession')); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->dropDownListRow($model, 'origin', TakType::items('origin')); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->dropDownListRow($model, 'employees', TakType::items('employees')); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->textFieldRow($model, 'email', array(
				'size' => 60,
				'maxlength' => 100
				)); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->textFieldRow($model, 'address', array(
				'size' => 60,
				'maxlength' => 255
				)); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->textFieldRow($model, 'telephone', array(
				'size' => 50,
				'maxlength' => 50
				)); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->textFieldRow($model, 'fax', array(
				'size' => 50,
				'maxlength' => 50
				)); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->textFieldRow($model, 'web', array(
				'size' => 50,
				'maxlength' => 50
				)); ?>
			</div>
			<div class="row-form clearfix hide">
				<?php echo $form->radioButtonListRow($model, 'display', Taktype::items('display') , array(
				'class' => '',
				'template' => '<label class="checkbox inline">{input}{label}</label>'
				)); ?>
			</div>
			<div class="row-form clearfix">
				<?php echo $form->textAreaRow($model, 'note', array(
				'size' => 60,
				'maxlength' => 255
				)); ?>
			</div>
			<?php if(Tak::getFormid()==1):?>
			<div class="row-form clearfix">
				<?php echo $form->textFieldRow($model, 'vipid', array(
				'size' => 50,
				'readonly'=>!Yii::app()->request->isPostRequest&&$model->vipid>0
				)); ?>
			</div>
			<?php endif?>

		</div>
	</div>

	<div class="footer tar">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		'size' => 'large',
		'buttonType' => 'submit',
		'label' => Tk::g($action)
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		'size' => 'large',
		'buttonType' => 'reset',
		'label' => Tk::g('Reset')
		)); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
</div>