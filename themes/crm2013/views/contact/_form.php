<?php
/* @var $this ContactController */
/* @var $model Contact */
/* @var $form bootstrap.widgets.TbActiveForm */
?>
<?php  $action = $model->isNewRecord?'Create':'Update';
$items = Tak::getEditMenu($model->itemid,$model->isNewRecord);
if (!$model->isNewRecord) {
$items['Create']['url'] = array('create','Contact[clienteleid]'=>$model->clienteleid,'Contact[prsonid]'=>$model->prsonid);
}
?>
<div class="row-fluid">
    <div class="span12">
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'contact-form',
        'type'=>'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>'true'),
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="head clearfix">
            <i class="isw-documents"></i><h1><?php echo Tk::g(array('Contact',$action));?></h1>
            <?php
            $this->widget('application.components.MyMenu',array(
                'htmlOptions'=>array('class'=>'buttons'),
                'items'=> $items ,
            ));
            $types = TakType::items('contact-type');
            ?>
        </div>
        <div class="block-fluid">
            <div class="row-form clearfix" >
                <?php echo $form->textFieldRow($model,'clienteleid',array('class'=>'select-clientele','size'=>20,'maxlength'=>20,'style'=>'width:100%')); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->textFieldRow($model,'prsonid',array('class'=>'select-prsonid','size'=>20,'maxlength'=>20,'style'=>'width:100%')); ?>
            </div>
            <div class="clear"></div>
            <div class="row-form clearfix">
                <?php echo $form->dropDownListRow($model,'type',$types); ?>
            </div>
            <div class="row-form clearfix">
                <?php echo $form->dropDownListRow($model,'stage',TakType::items('contact-stage')); ?>
            </div>
            <div class="row-form clearfix" >
                <?php echo $form->textFieldRow($model,'contact_time',array('class'=>'type-date','data-type'=>'now','data-type'=>'time','data-date-max'=>'now')); ?>
            </div>
            <div class="row-form clearfix" >
                <?php echo $form->textFieldRow($model,'next_contact_time',array('class'=>'type-date','data-type'=>'time','data-date-min'=>'now')); ?>
            </div>
            <div class="row-form clearfix" >
                <?php echo $form->textFieldRow($model,'next_subject',array('size'=>60,'maxlength'=>255)); ?>
            </div>
            <div class="row-form clearfix" >
                <div class="control-group ">
                    <?php echo $form->label($model,'accessory',array('class'=>'control-label')); ?>
                    <div class="controls">

                        <div id="container" class="itak-dr">
                            <button id="pickfiles" type="button">[选择文件]</button>
                            <?php echo $form->textField($model,'accessory',array('readonly'=>'true')); ?>
                            <div id="filelist"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-form clearfix" >
                <?php echo $form->textAreaRow($model,'note',array('size'=>60,'maxlength'=>255)); ?>
            </div>
        </div>
        <div class="footer tar">
            <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'submit', 'label'=>Tk::g($action))); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array('size'=>'large','buttonType'=>'reset', 'label'=>Tk::g('Reset'))); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<?php
    $iupload =  $this->widget('ext.Plupload.PluploadWidget', array(
        'config' => array(
        'pluploadPath' => true,
        'url' => $this->createUrl('/it/upload'),
        'browse_button' => 'pickfiles',
            'container' => 'container',
        ),
        'id' => 'uploader'
    ),true);
$postFileUrl = $this->createUrl('/it/upload');
$tempscript = "
var objUpload = {
runtimes : 'flash,html5,html4',
'url': '$postFileUrl',
flash_swf_url : '$iupload/Moxie.swf',
silverlight_xap_url : '$iupload/Moxie.xap',
browse_button:'pickfiles' ,
container:'container' ,
filters : {
    max_file_size : '10mb',
    mime_types: [
    {'title' : '图片|Image files', 'extensions' : 'jpg,gif,png,jpeg'},
    {'title' : '压缩包|Zip files', 'extensions' : 'zip,rar'},
    {'title' : '文档文件|Doc files', 'extensions' : 'doc,docx,xls,xlsx,rtf,txt,wps'}
    ]
}}
, uploader = new plupload.Uploader(objUpload)
;
$('#uploadfiles').click(function(e) {
uploader.start();
e.preventDefault();
});
uploader.init();
uploader.bind('PostInit', function(up) {
$('#filelist').html('');
});
uploader.bind('FilesAdded', function(up, files) {
plupload.each(files, function(file) {
document.getElementById('filelist').innerHTML += '<div id=\"' + file.id + '\"><a data-to=\"'+file.id+'\" href=\"javascript:;\" class=\"remove\"><i class=\"icon-trash\"></i></a>  ' + file.name + ' <b></b></div></div>';
});
up.refresh();
uploader.start();
});
uploader.bind('UploadProgress', function(up, file) {
$('#' + file.id + ' b').html(file.percent + '%');
});
uploader.bind('Error', function(up, err) {
$('#filelist').append('<div class=\"red\">提示: ' + err.message +(err.file ? ', 文件: ' + err.file.name :'') +'</div>'
);
up.refresh();
});
    uploader.bind('FileUploaded', function(up, file,msg) {
    var elem = $('#' + file.id);
        elem.find('b').html('100%');
        if (msg&&typeof msg['response']!='undefined') {
        var obj = $.parseJSON(msg['response']);
            if (obj&&typeof obj['result']!='undefined') {
        $('#Contact_accessory').val(obj.result);
                $('#pickfiles').hide();
        }
        }
    });
    $('#filelist').on('click',' a.remove',function(event) {
    event.preventDefault();
    var id = $(this).attr('data-to');
    uploader.removeFile(uploader.getFile(id));
    $('#' + id).remove();
    $('#pickfiles').show();
    $('#Contact_accessory').val();
    });
";
Yii::app()->clientScript->registerScript('', $tempscript, CClientScript::POS_READY);
?>