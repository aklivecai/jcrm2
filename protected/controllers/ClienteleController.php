<?php
class ClienteleController extends Controller {
    public function init() {
        parent::init();
        $this->modelName = 'Clientele';
    }
    
    protected function getSelectOption($q, $not = false) {
        $result = parent::getSelectOption(false, $not);
        $criteria = $result['data']['criteria'];
        if ($q) {
            $criteria->addSearchCondition('clientele_name', $q, true);
        }
        // Tak::KD($result,1);
        $result['data']['criteria'] = $criteria;
        return $result;
    }
    private function loadModelStatus($id, $status = 3) {
        $m = $this->modelName;
        $model = new $m('search');
        $model->setRecycle($status);
        $model = $model->setGetCU()->findByPk($id);
        if ($model == null) {
            $this->error();
        }
        $model->setGetCU()->setRecycle($status);
        return $model;
    }
    
    public function actionToSeas($id) {
        $model = $this->loadModel($id);
        if ($model->setSeas()) {
        }
        if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            'admin'
        ));
    }
    public function actionShowSeas($id) {
        $model = $this->loadModelStatus($id);
        
        $strView = 'show';
        
        $m = 'Contact';
        $mContact = new $m('search');
        $mContact->setGetCU();
        $arr = isset($_GET[$m]) ? $_GET[$m] : false;
        if ($arr) {
            $arr['clienteleid'] = $model->itemid;
            $mContact->attributes = $arr;
        } else {
            $mContact->attributes = array(
                'clienteleid' => $model->itemid
            );
        }
        
        if ($this->isAjax && ($arr || $_GET[$m . '_page'])) {
            $strView = 'contact';
        }
        if ($mContact->contact_time == 0) {
            $mContact->contact_time = '';
        }
        
        $this->render($strView, array(
            'model' => $model,
            'mContact' => $mContact,
        ));
    }
    public function actionGetSeas($id) {
        $model = $this->loadModelStatus($id);
        if (!$model->getBySeas()) {
            $this->errorE('错误!');
        }
        if (!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
            'view',
            'id' => $model->primaryKey
        ));
    }
    
    public function actionSeas() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->setGetCU()->setRecycle('3');
        $model->unsetAttributes();
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('seas', array(
            'model' => $model,
        ));
    }
    public function actionSelectByIdxx($id = false) {
        // header('Content-Type: application/json');
        if (!is_numeric($id)) {
            $message = Tk::g('Illegal operation');
            throw new CHttpException(403, $message);
            exit;
        }
        // $tags = Clientele::model()->published()->recently(3)->findAll();
        // Tak::KD($tags);
        $msg = Clientele::model()->find(array(
            'select' => 'itemid,clientele_name',
            'condition' => 'itemid=:itemid',
            'params' => array(
                ':itemid' => $id
            ) ,
        ));
        if ($msg != null) {
            $str = json_encode($msg->attributes);
            $this->writeData('{data:[' . $str . ']}');
        }
    }
}
