
<?php
/* @var $this ProductController */
/* @var $model Product */
$this->breadcrumbs = array(
    Tk::g($model->sName) => array(
        'admin'
    ) ,
    Tk::g('Admin') ,
);

$this->regScriptFile('linq.js');
?>
<div class="row-fluid">
    <div class="block-fluid clearfix">
<?php
$tags = $model->search();
$m = $tags->getData();
/** @var BootActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'search-form',
    'action' => Yii::app()->createUrl($this->route) ,
    'method' => 'get',
));
?>
<input type="hidden" name="index" value="<?php echo $_GET['index'] ?>" />
<table class="table table-search">
    <colgroup align="center">
    <col width="90px"/>
    <col width="125px"/>
    <col width="90px"/>
    <col width="125px"/>
    </colgroup>
    <tbody>
        <tr>
            <th><?php echo $form->label($model, 'company') ?></th>
            <td> <?php echo $form->textField($model, 'company'); ?></td>
            <th>
            <?php echo $form->label($model, 'serialid') ?></th>
            <td><?php echo $form->textField($model, 'serialid'); ?></td>
            <td></td>
        </tr>
        <tr>
            <th><?php echo CHtml::label('产品', 'info-product') ?></th>
            <td>
                <?php echo CHtml::textField('info-product', $_GET['info-product']) ?>
            </td>
                <th><?php echo $form->label($model, 'note') ?></th>
                <td colspan="2"> <?php echo $form->textField($model, 'note', array()); ?></td>
            </tr>
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <td colspan="4">
                <?php echo JHtml::htmlButton(Tk::g('Search') , array(
    'class' => 'btn',
    'type' => 'submit'
)) ?>
            </td>
        </tr>
        </tfoot>
    </table>
    <?php
$this->endWidget();
?>

<?php
$template = "<div class=\"list-view\">{pager}</div>\n<table class=\"items table table-striped table-bordered table-condensed\">
    <colgroup>
    <col width=\"auto\"  span=\"3\">
    <col width=\"80px\"/>
    </colgroup>
            <thead> <tr> 
                <th>订单产品</th>  
                <th>{$model->getAttributeLabel('company') }</th>
                <th>{$model->getAttributeLabel('serialid') }</th>
                <th></th>
                </tr> </thead> <tbody>{items}</tbody> </table>\n<div class=\"list-view\">{pager}</div>";
$this->widget('bootstrap.widgets.TbListView', array(
    'id' => $typeid,
    'dataProvider' => $tags,
    'itemView' => '_orders',
    'template' => $template,
    'htmlOptions' => array(
        'class' => ''
    ) ,
    'ajaxUpdate' => null,
    'ajaxUpdate' => true,
    'emptyText' => '<tr><td colspan="4">没有数据!</td></tr>',
    'viewData' => array(
        'cates' => $cates
    )
));
?>
        </div>
    </div>
<?php
Tak::regScript('end', '
    if(window.opener == undefined) {
        window.opener = window.dialogArguments;
    }
window.setProduct = function(data){ 
    opener.addData(data,"' . $_GET['index'] . '");
    window.close();   
};');
?>
