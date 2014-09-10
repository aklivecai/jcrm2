<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs = array(
    '发送邮件',
);
$items = $msg->getItems(5);
$this->widget('zii.widgets.CMenu', array(
    'items' => $items,
));
?>

<!-- ▼显示提示信息▼ -->  
<?php if (Yii::app()->user->hasFlash('success')) : ?>  
<div class="flash-success">  
    <?php echo Yii::app()->user->getFlash('success'); ?>  
</div>  
<?php endif  ?>

<div class="form">  
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'mail-form',
    'method' => 'post',
    'enableClientValidation' => true,
    'clientOptions' => array() ,
));

if (!$model->body) {
    $model->body = $this->renderPartial('../email', array(
        'model' => $msg,
    ) , true);
    // echo $model->body;
    
}
?>  
<style type="text/css">
    .rowi{
        float: left;
        display: inline;
        margin-right: 8px;
    }
</style>
  <!-- 送信元 -->  
    <div class="rowi">  
        <?php echo $form->labelEx($model, 'from'); ?>  
        <?php echo $form->textField($model, 'from'); ?>  
        <?php echo $form->error($model, 'from'); ?>  
    </div>  
    <!-- 送信先 -->  
    <div class="rowi">  
        <?php echo $form->labelEx($model, 'to'); ?>  
        <?php echo $form->textField($model, 'to'); ?>  
        <?php echo $form->error($model, 'to'); ?>  
    </div>  
    <!-- 件名 -->  
    <div class="rowi">  
        <?php echo $form->labelEx($model, 'subject'); ?>  
        <?php echo $form->textField($model, 'subject'); ?>  
        <?php echo $form->error($model, 'subject'); ?>  
    </div>
    <hr />  
    <!-- 内容 -->  
    <div class="row clear">  
        <?php echo $form->labelEx($model, 'body'); ?>  
        <?php echo $form->textArea($model, 'body', array(
    'cols' => '80',
    'rows' => '10',
)); ?>  
        <?php echo $form->error($model, 'body'); ?>  
    </div>  
          
    <div class="row  ">  
            <?php echo CHtml::Button('发送邮件', array(
    'submit' => array() ,
    'params' => array(
        'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken
    ) ,
));
?>  
            <button  type="button" onclick="tak()">预览邮件内容</button>
    </div>  
  
<?php $this->endWidget(); ?>  
</div>  

<script type="text/javascript" src="http://www.9juren.com/_ak/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
/*<![CDATA[*/
// setData
var iedit = CKEDITOR.replace( 'MailForm[body]', {
         toolbar : 'Email'
        ,height :   350
        ,allowedContent :true
        ,startupOutlineBlocks :false
});    

function tak () {
    var data = iedit.getData();
    var mywin = window.open("", "ckeditor_preview", "location=0,status=0,scrollbars=0,width=800,height=680");
    mywin.document.write(data);
}
/*]]>*/
</script>
