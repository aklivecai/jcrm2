<?php
 $form = $this->beginWidget('CActiveForm', array(
	'id'=>'import',
	'action'=>array('/Import/Import?action='.$action)
	// 'action'=>'/_/i.php?action='.$action
)); ?>
	<div class="controls">
		<?php 
			$this->renderPartial('_'.$action); 
		?>
		<a class="btn"  href="<?php echo $this->createUrl("Clears",array('action'=>$action))?>">清空</a>
		<button class="btn" id="btn-submit" type="sublime">提交</button>

		<div class="dr"><span></span></div>
		<h6>总数:
		<span class="label label-warning">
			<?php echo  count($data)?>
		</span>,请正确填写<span class="red">红色</span>文本框的内容!</h6>
	</div>
	<div class="dr"><span></span></div>
	<table class="items table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<?php foreach($header as $value):?>
				<th><?php echo $value?></th>
				<?php endforeach ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $key=>$value):?>
			<tr>
				<?php foreach($value as $k=>$v):?>
				<td><?php 
					$id = JImportForm::colName($action,$k,$key);
					// $arr = array('id'=>$id);
					$arr = array();
					$_txt = '';
					if ($action=='order'&&($k=='A'||$k=='K')&&(is_numeric($v)&&$v>1000&&$v<900000)) {
						$v = Ak::ExcelDateToPhpDate($v);
						$v = Ak::timetodate($v);
					}
					if ($action=='product') {
						if ($k=='H'||$k=='I') {
							// $arr['min']	= 0;
							$arr['required'] = true;
							if (!$v) {
								$v = 0;
							}
							$_txt = JHtml::numberField($id,$v,$arr);
						}						
					}
					if ($_txt=='') {
						$v = str_replace('"', "", $v);
						$_txt = JHtml::textField($id,$v,$arr);
					}
					echo $_txt;
				?></td>
				<?php endforeach ?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php $this->endWidget(); ?>