<?php
/* @var $this ProductController */
/* @var $model Product */
	$this->breadcrumbs=array(
		$model->sName => '#',
		Tk::g('Admin'),
	);
	$action = $model->isNewRecord?'Create':'Update';
?>
		<?php 
			if ($this->type&&$this->type['file']) {
				$this->renderPartial("//{$this->type['file']}/type",array('model'=>$model,)); 
			}
		?>
<div class="tab-content">
   <div class="row-fluid">
                    <div class="span6">
                        <div class="head clearfix">
                            <div class="isw-list"></div>
                            <h1><?php echo Tk::g('List'); ?></h1>
                        </div>

                        <div class="block-fluid clearfix">
					<?php 
					$tags = $listM->search();
					$widget = $this->widget('bootstrap.widgets.TbGridView', array(
							    'type'=>'striped bordered condensed',
							    'id' => 'list-grid',
								'dataProvider'=>$listM->search(),
								'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
								'enableHistory'=>true,
							    	'loadingCssClass' => 'grid-view-loading',
							    	'summaryCssClass' => 'dataTables_info',
							    	'pagerCssClass' => 'pagination dataTables_paginate',
							    	'ajaxUpdate'=>true,    //禁用AJAX
							    	'enableSorting'=>false,
								'columns'=>array(
									array(
										'name'=>'typename',
										'type'=>'raw',
									),
									array(
										'name'=>'listorder',
										'type'=>'raw',
										'headerHtmlOptions'=>array('style'=>'width: 85px'),
									),
									array(
										 'class'=>'bootstrap.widgets.TbButtonColumn'
										  ,'htmlOptions'=>array('style'=>'width: 85px')
										  ,'template' => '{update} {delete}'
										  , 'deleteButtonUrl' => '$data->getDelLink()'
										  , 'updateButtonUrl' => '$data->getEidtLink()'

									),			
								),
							)); 
							?>
				</div>
			</div>
	<div class="span6">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1><?php echo Tk::g($action); ?></h1>
                        </div>
                        <div class="block  clearfix">        
						<?php 
						$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
							'id'=>'address-groups-form',
							 'type'=>'verticalForm ',
							 'enableAjaxValidation'=>false,
							 'htmlOptions'=>array('class'=>'well'),
							 'focus'=>array($model,'name'),
							 'action'=>$model->getEidtLink(),  
						)); ?>
						<?php echo $form->errorSummary($model); ?>
						<?php echo CHtml::hiddenField('returnUrl', $this->typeUrl);?>
						<?php echo $form->hiddenField($model,'item'); ?>
						<?php echo $form->textFieldRow($model,'typename',array('size'=>60,'maxlength'=>255)); ?>
						<?php echo $form->textFieldRow($model,'listorder',array('size'=>60,'maxlength'=>255)); ?>
						<div class="">
							<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g($action),'htmlOptions'=>array())); ?>
						</div>
						<?php $this->endWidget(); ?></div>
				</div>
			</div>			
			<div class="dr"><span></span></div>
		</div>
	</div>
<?php
if ($tags->getTotalItemCount()==0) {
    $_tname = $model->getAttribute('typename');
    echo $_tname;
    Tak::regScript('bodyend-',
    "   intro =new  introJs();
           intro.setOptions({
                    steps: [
                      {
                        element: document.querySelector('input[name*=typename]'),
                        intro: '$_tname.',
                        position: 'button'
                      }
                    ]
              });
            intro.start();          
    "
    );	
}
?>