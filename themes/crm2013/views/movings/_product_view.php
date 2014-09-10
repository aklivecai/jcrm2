<tr <?php if (isset($data->iProduct)) {echo sprintf(' class="data-ajax" data-url="%s"  title="%s"',$data->iProduct->getLink(false,'preview'),$data->iProduct->name); }?>>
	<td><?php echo $data->iProduct->name; ?></td>
	<td><?php echo CHtml::encode($data->iProduct->spec); ?></td>
	<td><?php echo CHtml::encode($data->iProduct->material); ?></td>
	<td><?php echo CHtml::encode($data->iProduct->color); ?></td>
	<td class="txt-center"><?php echo  Tak::format_price($data->price); ?></td>	
	<td class="txt-right"><?php echo Tak::getNums($data->numbers); ?></td>	
	<td class="txt-center"><?php echo CHtml::encode($data->iProduct->unit); ?></td>
</tr>