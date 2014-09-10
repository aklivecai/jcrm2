<?php
/**
 *  设置审批流程的步骤和表单
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-07 14:51:44
 * @version $Id$
 */
$this->breadcrumbs[] = Tk::g(array(
    $model->flow_name,
    ' - ',
    "Setting",
));
Tak::regScriptFile('_ak/js/doT.min.js', 'static');
$this->addScriptFile('load-flowInfo-view.js');
?>
<div class="page-header">
    <h1><?php echo $model->flow_name; ?> <small><?php echo Tk::g('Setting') ?></small></h1>
</div>
<div class="wap-body">
    <div class="span5">
        <div class="block-fluid without-head">
            <div class="toolbar nopadding-toolbar clear clearfix">
                <h4>步骤设置</h4>
            </div>
            <div class="">
                <table class="items table  min-table">
                    <colgroup>
                    <col width="55px"/>
                    <col width="80px" />
                    <col width="80px" />
                    <col width="80px" />
                    <col width="auto" />
                    <col width="60px" />
                    </colgroup>
                    <thead>
                        <th>步骤</th>
                        <th>名称</th>
                        <th>处理人</th>
                        <th>超时时间</th>
                        <th>条件/设置</th>
                        <th>操作</th>
                    </thead>
                    <tbody>
                        <tr id="1"><td>第<span class="label">1</span>步</td><td>开始</td><td>发起人员</td><td>2天</td>
                        <td></td>
                        <td>
                            <a class="update" title="更新" href="#"><i class="icon-pencil"></i></a>
                            <a class="delete" title="删除"  href="#"><i class="icon-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td ><button id="add_field" class="btn btn-info btn-small" disabled="">添加</button></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="span7">
    <div class="block-fluid without-head">
        <div class="toolbar nopadding-toolbar clear clearfix">
            <h4>表单字段</h4>
        </div>
        <div class="">
            <table class="items table  min-table">
                <colgroup>
                <col width="15%"/>
                <col width="80px" />
                <col width="20%"  span="2" />
                <col width="auto" />
                <col width="60px" />
                </colgroup>
                <thead>
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
                </thead>
                <tbody>
                    <tr id="base_field">
                        <td><input required="required" style="width:95%" name="FormField[0][field_name]" id="FormField_0_field_name" type="text" /></td>
                        <td><select class="inline type_select" style="width:95%" name="FormField[0][field_type]" id="FormField_0_field_type">
                            <option value="int">文本</option>
                            <option value="select">选择(勾选)</option>
                            <option value="date">日期</option>
                        </select></td>
                        <td><input style="width:95%" name="FormField[0][field_default]" id="FormField_0_field_default" type="text" /></td>
                        <!-- <td></td> -->
                        <td><input style="width:95%" name="FormField[0][field_desc]" id="FormField_0_field_desc" type="text" /></td>
                        <td>
                        </td>
                        <td>
                            <a class="update" title="更新" href="#"><i class="icon-pencil"></i></a>
                            <a class="delete" title="删除"  href="#"><i class="icon-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td ><button id="add_field" class="btn btn-info btn-small">添加</button></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php
$row = 0;
foreach ($allModel as $model) {
    ++$row;
    $user = Tool::getUname($model->step_user);
    $href = $this->createUrl('condition', array(
        'step_id' => $model->step_id,
        'form_id' => $form_id
    ));
    $json = array(
        'id' => $model->step_id,
        'step_name' => $model->step_name,
        'row' => $row,
        'step_no' => $model->step_no,
        'step_user' => $model->step_user,
        'user_label' => $user,
        'alluser' => Tool::userListJs($model->step_user) ,
        'timeout' => $model->timeout,
        'href' => $href,
        'type' => 'step',
    );
    $conLabel = empty($model->condition) ? '无条件' : '有条件';
    $json = htmlspecialchars(json_encode($json));
    if ($row == 1) {
    }
}
//设置增加步骤表单
$model = new FLowStep;
?>
<div class="dr"><span></span></div>
</div>

<script id="data-step" type="text/x-dot-template">
<tr id="{{=it.id}}">
<td>
    第<span class="label">{{=it.row || ''}}</span>步
</td>
<td>
    <input type="text" required="required" value="{{=it.step_name || ''}}" name="step_name"/>
</td>
<td>
<select name="step_user" required="required">{{=it.alluser || ''}}</select>
</td>
<td>
<input type="text" required="required" value="{{=it.timeout || ''}}" name="timeout"/>天
</td>
<td>
</td>
<td>
<a target="_blank" href="{{=it.href}}">设置审批条件</a>
</td>
<td>
<a title="保存" href="javascript:;" class="ok">保存</a>&nbsp;
<a title="取消" href="javascript:;" class="circle">取消</a>
</td>
</tr>
</script>

<script id="view-step" type="text/x-dot-template">
<tr id="{{=it.id}}">
<td>
第<span class="label">{{=it.row || ''}}</span>步
</td>
<td>
{{=it.step_name || ''}}
</td>
<td>
{{=it.user_label || ''}}
</td>
<td>
{{=it.timeout || ''}}天
</td>
<td>
</td>
<td>
<a target="_blank" href="{{=it.href}}">设置审批条件</a>
</td>
<td>
<a href="javascript:;" title="编辑" class="edit" data-json="{{=it.json}}">编辑</a>&nbsp;
<a href="javascript:;" title="删除" class="s_delete" data-id="{{=it.id}}">删除</a>&nbsp;
</td>
</tr>
</script>