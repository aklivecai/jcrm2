<?php
$this->breadcrumbs = array(
Tk::g('Order') => array(
'order/admin'
) ,
Tk::g('Alipay') ,
);
?>
<div class="page-header">
    <h1><?php echo Tk::g('Alipay') ?></h1>
</div>
<div class="block-fluid">

    <div class="row-fluid">
        <?php $this->tab(); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'mod-form',
        'type' => 'horizontal',
        ));
        echo $form->errorSummary($model);
        ?>
        <table class="table table-search">
            <colgroup align="center">
            <col width="10%"/>
            </colgroup>
            <tbody>
                <tr>
                    <th>
                        <?php echo Tk::g('Alipay'); ?>
                    </th>
                    <td>
                    <?php 
                    $htmls = array();
                    foreach ($dalipays as $key => $value) {
                        $htmls[] = sprintf('<label class="checkbox inline"><input type="checkbox" name="values[]" value="%s" %s/>%s</label>'
                            ,$key
                            ,isset($alipays[$key])?' checked ':''
                            ,$value
                        );
                    }
                    echo implode("\n",$htmls);
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>账号信息:</th>
                    <td>
                        <?php $this->widget('application.extensions.HtmlEdit', array(
                        'name' => 'tak_content',
                        'value' => $model->content,
                        'options' => array(
                        'toolbar' => 'Edit',
                        'height' => 200,
                        'allowedContent' => false,
                        'startupOutlineBlocks' => false,
                        )
                        )); ?>
                    </td>
                </tr>
            </tbody>
            <tfoot>
            <th></th>
            <td>
                <?php echo JHtml::htmlButton(Tk::g('Save') , array(
                'class' => "btn",
                'type' => "submit"
            )) ?></td>
            </tfoot>
        </table>
        <?php $this->endWidget(); ?>
    </div>

</div>
</div>