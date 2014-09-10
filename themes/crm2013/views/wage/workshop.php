<?php

$this->breadcrumbs = array(
    Tk::g($this->modelName) => array(
        'admin'
    ) ,
    $model->title,
);
$this->renderPartial('_workshop_tabs', array(
    'model' => $model,
    'id' => $id
));

$scrpitS = array(
    '_ak/js/lib.js',
);
Tak::regScriptFile($scrpitS, 'static', null, CClientScript::POS_END);
$scrpitS = array(
    'k-load-wage-workshop.js?tt123',
);
$this->regScriptFile($scrpitS, CClientScript::POS_END);

$tags = CJSON::encode(array_values($data));
Tak::regScript('footer', '
var  tags = ' . $tags . '
, saveUrl = "' . $this->createUrl('') . '/../"
;
', CClientScript::POS_HEAD);
?>

		<h2><?php echo Tk::g(array(
    'Workshop',
    'Setting'
)) ?></h2>