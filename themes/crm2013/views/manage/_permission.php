<div class="row-fluid">
    <div class="span6">
        <div class="head clearfix">
            <i class="isw-documents"></i> <h1><?php echo Tk::g(array(
    'Jurisdiction'
)); ?></h1>
        </div>
        <div class="block clearfix">
            <div class="add-assignment">
                <?php if ($formModel !== null): ?>
                <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'userAssignments',
        'type' => 'search',
    ));
    echo JHtml::label(Rights::t('core', 'Assign item') . ':', 'AssignmentForm_itemname', array(
        'class' => 'label-horizontal'
    ));
    echo $form->dropDownList($formModel, 'itemname', $assignSelectOptions);
    echo $form->error($formModel, 'itemname');
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label' => Rights::t('core', 'Assign')
    ));
    $this->endWidget();
?>
                <?php
else: ?>
                <p class="info">
                <?php echo Rights::t('core', 'No assignments available to be assigned to this user.'); ?>
                </p>
                <?php
endif; ?>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                <thead>
                    <tr>
                        <th width="15%">类型</th>
                        <th width="65%">名字</th>
                        <th width="20%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataJurisdiction as $key => $value): ?>
                    <tr <?php if ($value['active']) {
        echo 'class="unedit"';
    } ?>>
                        <td><?php echo $value['typeName'] ?></td>
                        <td><?php echo $value['title'] ?></td>
                        <td>
                            <?php
    if (!$value['active']) {
        echo JHtml::link('<i class="icon-remove"></i>' . Tk::g('Revoke') , array(
            'revoke',
            'id' => $this->setSId($model->primaryKey) ,
            'name' => $value['id']
        ) , array(
            'class' => 'revoke-link'
        ));
        echo '&nbsp;|&nbsp;';
    }
    
    if ($value['type'] == 2 && is_numeric($value['name'])) {
        echo JHtml::link('<i class="icon-eye-open"></i>' . Tk::g('View') , array(
            'permission/preview',
            'id' => $this->setSId($value['name']) ,
        ) , array(
            'class' => 'data-ajax',
            'title' => Tk::g(array(
                'View',
                ' 「' . $value['title'] . '」',
                'Jurisdiction'
            ))
        ));
    }
?>
                        </td>
                    </tr>
                    <?php
endforeach
?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="span6">
        <div class="head clearfix">
            <i class="isw-user"></i>
        </div>
        <div class="block clearfix">
            <?php
$data = $subusers->getData();
$subusers->manageid = null;
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'userSubusers',
    'type' => 'search',
    'action' => $this->getJSubUrl()
));
echo $form->errorSummary($subusers);
echo JHtml::label($subusers->getAttributeLabel('manageid') . ':', 'Subordinate_manageid', array(
    'class' => 'label-horizontal'
));
$q = array(
    'id=' . $model->primaryKey,
);
echo $form->textField($subusers, 'manageid', array(
    'class' => 'select-ajax',
    'data-select' => 'Subordinate',
    'data-get' => 'Manage',
    'data-action' => 'users',
    'data-path' => join(' &', $q) ,
    'size' => 10,
    'style' => 'width:180px'
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'label' => Rights::t('core', 'Assign')
));
$this->endWidget();
?>
            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                <thead>
                    <tr>
                        <th width="35%">部门</th>
                        <th width="40%">名字</th>
                        <th width="25%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value): ?>
                    <tr <?php if ($value['active']) {
        echo 'class="unedit"';
    } ?>>
                        <td><?php echo $value['branch_name'] ?></td>
                        <td><?php echo $value['user_nicename'] ?></td>
                        <td>
                            <?php
    echo JHtml::link('<i class="icon-eye-open"></i>' . Tk::g('View') , array(
        'preview',
        'id' => $this->setSId($key) ,
    ) , array(
        'class' => 'data-ajax',
        'title' => Tk::g(array(
            'View',
            ' 「' . $value['user_nicename'] . '」',
        ))
    ));
    if (!$value['active']) {
        echo '&nbsp;|&nbsp;';
        echo JHtml::link('<i class="icon-remove"></i>' . Tk::g('Revoke') , array(
            'RevokeSub',
            'id' => $this->setSId($model->primaryKey) ,
            'name' => $this->setSId($key)
        ) , array(
            'class' => 'revoke-link'
        ));
    }
?>
                        </td>
                    </tr>
                    <?php
endforeach
?>
                </tbody>
            </table>
        </div>
    </div>
</div>