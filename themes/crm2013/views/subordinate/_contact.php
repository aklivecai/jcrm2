  <tr>
  	<td><?php echo Tak::timetodate($data->contact_time,5); ?></td> 
  	<td title="联系人详情" class="data-ajax" role='view' data-url="<?php echo Tak::creaetPreviewUrl(array('id'=>$data->prsonid,'url'=>'ContactpPrson','not'=>true)) ?>"><?php echo CHtml::encode($data->iContactpPrson->nicename)?></td>  
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