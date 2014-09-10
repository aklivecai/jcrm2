<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'admin'
    ) ,
    $model->title,
);
$this->renderPartial('_tabs', array(
    'model' => $model,
    'id' => $id
));
?>
<div class="tab-content">
    <div class="row-fluid">
        <div class="span6">
            <div class="head clearfix">
                <div class="isw-users"></div>
                <h1><?php echo Tk::g('权限'); ?></h1>
            </div>

            <div class="block-fluid users">
                <?php if (count($data) > 0): ?>
                <?php foreach ($data as $key => $value): ?>
                <div class="item clearfix">
                    <div class="info">
                        <?php
        $_type = Jurisdiction::getTypeName($value['type']);
        echo CHtml::link($value['description']
        // ,array('show','id'=>$id,'child'=>urlenc\ode($crypt->encrypt($value['name'])))
        , "#", array(
            'class' => 'name data-ajax--',
            'title' => Tk::g(array(
                $_type,
                ' - ',
                $value['description']
            ))
        ));
?>
                        <span>
                        <?php echo $_type; ?>
                        </span>
                        <div class="controls">
                            <?php
        echo CHtml::link('<i class="icon-remove"></i>' . Tk::g('Revoke') , array(
            'RemoveChild',
            'id' => $id,
            'child' => urlencode(Tak::encrypt($value['name']))
        ) , array(
            'class' => 'revoke-link'
        ));
?>
                        </div>
                    </div>
                </div>
                <?php
    endforeach ?>
                <?php
else: ?>
                <div class="item clearfix">
                    <?php echo '还没有分配!'; ?>
                </div>
                <?php
    if (count($this->tabs) <= 1) {
        Tak::regScript('bodyend-', "   intro =new  introJs();
                intro.setOptions({
                steps: [
                {
                element: document.querySelector('#horizontalForm'),
                intro: '给部门分配需要的权限.',
                position: 'button'
                }
                ]
                });
                intro.start();
                ");
    }
?>
                <?php
endif ?>


            </div>
        </div>
        <div class="span6">
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo Tk::g('Add'); ?></h1>
            </div>
            <div class="block  clearfix">
                <?php if ($childFormModel !== null): ?>
                <?php $this->renderPartial('_childForm', array(
        'model' => $childFormModel,
        'itemnameSelectOptions' => $childSelectOptions,
    )); ?>

                <?php
else: ?>
                <p class="info"><?php echo Rights::t('core', 'No children available to be added to this item.'); ?>
                <?php
endif; ?>
            </div>
        </div>
    </div>
    <div class="dr"><span></span></div>
</div>

</div>