<tr>
  <td><span class="date"><?php echo TakType::getStatus('origin',$data->origin);?></span></td>
  <td><?php echo $data->getHtmlLink();?></td>
  <td><span class="time"><?php echo Tak::timetodate($data->add_time,4);?></span></td>
</tr>