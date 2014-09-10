<!-- 表单列表页面，未使用 -->
<div class="masthead">
    <span class="label label-info">所有流程表单</span>
    <span class="right">
        <div>
            <a class="btn btn-primary" href=<?php echo $this->createUrl("Create");?>>新建流程表单</a>
        </div>
    </span>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    'enablePagination'=>true,
    'template'=>"{items}{pager}",
    'emptyText'=> '没有数据.',
    'columns'=>array(
        array('name'=>'form_id', 'header'=>'#'),
        array('name'=>'form_name', 'header'=>'流程名称'),
        // array('name'=>'begin_user', 'header'=>'处理人'),
        // array('name'=>'run_prc.step_time', 'header'=>'处理期限'),
        // array('name'=>'describe', 'header'=>'描述'),
        // array('name'=>'step_no', 'header'=>'步骤号'),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'template' => '{preview}',
            'class'=>'bootstrap.widgets.TbButtonColumn',
            // 'viewButtonUrl'=>'Yii::app()->controller->createUrl("detail", array("form_id"=> $data["form_id"]))',
            // 'viewButtonOptions'=>array('target'=>'_blank'),
            'buttons'=>array(
                    'preview'=>array(
                        'label'=>'预览表单',
                        'text'=>'预览表单',
                        'options'=>array('title'=>'', 'target'=>'_balnk'),
                        'url'=> 'Yii::app()->controller->createUrl("detail", array("form_id"=> $data["form_id"]))',
                        'imageUrl'=>false,
                        ),
                ),
        ),
    ),
));

?>