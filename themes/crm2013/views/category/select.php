<div class="row-fluid input-prepend input-append">
    <?php  
    	$parentname = Category::getProductName($value);
	    echo JHtml::hiddenField($id,$value,array(
	        'class' => 'sourceField'
	    ));    
     ?>
    <input name="popupReferenceModule" type="hidden" value="product">
    <span class="add-on clearReferenceSelection cursorPointer">
    <i class='icon-remove-sign' title="清除"></i>
    </span>
    <input  name="vendor_id_display" type="text" class="span7" value="<?php echo $parentname ?>" placeholder="请选择分类" readonly="readonly" disabled="disabled"/>
    <span class="add-on relatedPopup cursorPointer">
    <i class="icon-search " title="Select" ></i>
    </span>
    <span class="add-on createPopup cursorPointer <?php if(!isset($add)){echo'hide';}?>"  title="添加分类">
        <i class='icon-plus'></i>
    </span>
    <span class="help-inline error" id="Category_parentid_em_" style="display: none"></span>
</div>