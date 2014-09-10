<?php
/*
* 表单设置页面
*/
?>
<table id="are_field" class="container  table table-striped table-bordered table-condensed">
    <colgroup>
    <col width="20%"/>
    <col width="80px" />
    <col width="20%"  span="2" />
    <col width="auto" />
    <col width="90px" />
    </colgroup>
    <thead>
        <tr>
            <th> <?php echo $fieldModel->getAttributeLabel('field_name'); ?>
            </th>
            <th>
                <?php echo $fieldModel->getAttributeLabel('field_type'); ?>
            </th>
            <th>
                <?php echo $fieldModel->getAttributeLabel('field_default'); ?>
            </th>
            <th>
                <?php echo $fieldModel->getAttributeLabel('field_desc'); ?>
            </th>
            <th>
                <?php echo $fieldModel->getAttributeLabel('field_value'); ?>
            </th>
            <th>
                操作
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 显示已有字段数据
        foreach ($allFieldModel as $model) {
        echo '<tr><td>', $model->field_name, '</td><td>', $model->label[$model->field_type], '</td><td>', $model->field_default, '</td><td>', $model->field_desc, '</td><td>', $model->field_value, '</td><td>', '<a class="delete btn btn-small" href="#" data-id="', $model->field_id, '">删除</a></td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="5"></td>
        <td >
            <a id="add_field" class="btn btn-info btn-small">添加字段</a>
        </td>
    </tr>
    </tfoot>
</table>