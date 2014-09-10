<div class="control">
	<label for="warehouse_id">
	如果要导入初始库存数,请选择仓库:
	</label>
	<?php echo  JHtml::dropDownList('warehouse_id'
				,$_POST['warehouse_id']
				,Warehouse::toSelects('选择仓库')
				,array('id'=>'warehouse_id')
			);
	?>
</div>