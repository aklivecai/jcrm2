<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'search-form',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well','to-view'=>'list-views'),
    'action' => Yii::app()->createUrl($this->route,array('id'=>$model->itemid)),
    'method'=>'get',
)); 

  echo $form->dropDownList($mContact,'stage',TakType::items('contact-stage',0,'阶段')); 

  echo $form->textFieldRow($mContact,'contact_time',array('size'=>10,'maxlength'=>10,'class'=>'type-date')); 

  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Tk::g('Search'))); 
  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>Tk::g('Reset'),'htmlOptions'=>array('class'=>'btn-reset'))); 

  echo CHtml::button(Tk::g('Reset'),array('type'=>'reset','class'=>'hide'));

 $this->endWidget(); 
 
$template ="{pager}".CHtml::dropDownList('pageSize'
                    ,Yii::app()->user->getState('pageSize')
                    ,TakType::items('pageSize')
                    ,array(
                        'onchange'=>"$.fn.yiiListView.update('list-views',{data:{setPageSize: $(this).val()}})", 
                        'class'=>'select-page',
                    ))."{summary}<table class=\"table\"> <thead> <tr> 
      <th width='100px'>{$mContact->getAttributeLabel('contact_time')}</th>
      <th width='60px'>{$mContact->getAttributeLabel('prsonid')}</th>
      <th width='80px'>{$mContact->getAttributeLabel('type')}</th>
      <th width='80px'>{$mContact->getAttributeLabel('stage')}</th>  
      <th width='120px'>{$mContact->getAttributeLabel('next_contact_time')}</th>
      <th width='100px'>{$mContact->getAttributeLabel('next_subject')}</th>    
      <th>{$mContact->getAttributeLabel('note')}</th>    
      <th width='40px'>{$mContact->getAttributeLabel('accessory')}</th>      
      </tr> </thead> <tbody>{items}</tbody> </table>" ;

      // with('iContactpPrson')
 $this->widget('bootstrap.widgets.TbListView'
    , array(
        'id' =>'list-views',
        'enableHistory'=>true,
        'afterAjaxUpdate'=>'afterListView',
        'dataProvider' => $mContact->search() ,
        'itemView'=>'_show_contact',
        'loadingCssClass' => 'grid-view-loading',
        'template'=>$template,
        'htmlOptions'=>array('class'=>''),
        'emptyText'=>'<tr><td colspan="7" class="not-data"></td></tr>',
  ));
     ?>    	