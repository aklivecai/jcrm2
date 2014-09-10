<?php
class IakController extends Controller {
    public function allowedActions() {
        return 'index';
    }
    public function init() {
        Yii::app()->clientScript->enableJavaScript = false;
    }
    public function afterRender($view, &$output) {
        Yii::app()->clientScript->reset();
        parent::afterRender($view, $output);
    }
    public function actionIndex() {
        // header('Content-Type: application/json');
        $sql = sprintf("SELECT itemid,name,material,color,unit,spec,price FROM {{product}}  WHERE fromid=%s ", Tak::getFormid());
        // ORDER BY name DESC LIMIT 0,10000
        // $data = array($sql);
        // $sql = implode(' UNION ALL ',$data);
        $com = Yii::app()->db->createCommand($sql);
        $rows = $com->queryAll();
        printf("tags=%s", json_encode($rows));
        exit;
    }
}
