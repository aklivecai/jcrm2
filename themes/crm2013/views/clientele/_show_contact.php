<?php
          $name = $data->iContactpPrson->nicename?$data->iContactpPrson->nicename:'查看联系人';
?>
  <tr>
    <td><?php echo Tak::timetodate($data->contact_time,5); ?></td>    
       <td class="data-ajax" title="<?php echo $name?>" data-url="<?php echo Yii::app()->createUrl('contactpprson/preview',array('id'=>$data->prsonid))?>">
       <?php echo CHtml::encode($name);?></td>   
  	<td><?php echo TakType::getStatus('contact-type',$data->type)?></td>
  	<td><?php echo TakType::getStatus('contact-stage',$data->stage)?></td>
  	<td><?php echo Tak::timetodate($data->next_contact_time,5); ?></td>
  	<td><?php echo CHtml::encode($data->next_subject)?></td>
  	<td>
  		<?php echo CHtml::tag('div',array('class'=>$data->note!=''?'more-info':''),CHtml::encode($data->note))?>
  	</td>    
    <td>
      <?php 
      if ($data->accessory) {
        echo CHtml::link('<i class="icon-tags"></i>查看',$data->accessory,array('target'=>'_blank','title'=>$data->accessory));
      }        
      ?>
    </td>
  </tr>  