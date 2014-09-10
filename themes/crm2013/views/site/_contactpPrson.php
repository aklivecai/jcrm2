<div class="item">
<?php echo $data->getHtmlLink() ;?>
<p>
	公司：<?php echo $data->iClientele?$data->iClientele->clientele_name:'';?>
	<br />
	联系方式：<?php echo $data->mobile.' / '.$data->phone;?>  
</p>
<span class="date"><?php echo Tak::timetodate($data->last_time,6);?></span>
<div class="controls">
  <a href="<?php echo Yii::app()->createUrl('contact/create',array('Contact[prsonid]'=>$data->itemid,'Contact[clienteleid]'=>$data->clienteleid,));?>" class="icon-plus" title="添加联系记录" data-original-title="Edit"></a>
</div>
</div>