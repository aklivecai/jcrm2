<div class="item clearfix">
<div class="image">
	<?php echo TakType::getStatus('contact-type',$data->type);?>
</div>
<div class="info"> 
	<a href="<?php echo $data->getLink() ;?>">
		<?php echo isset($data->iContactpPrson)?$data->iContactpPrson->nicename:''; ?>&nbsp;
	</a>
  <span class="time">
  	<?php echo Tak::timetodate($data->contact_time,3);?>
  </span>
  <div class="controls">
  	<?php echo TakType::item('contact-stage',$data->stage);?>
  </div>
</div>
</div>