<?php
class SubordinateController extends Controller {
    public $defaultAction = 'index';
    public $users = array();
    
    public function init() {
        parent::init();
        $this->modelName = 'SubClientele';
        $this->users = Subordinate::getUsers();
        // $this->users = Manage::model()->findAllByAttributes(array('branch' => Tak::getState('isbranch',-1)));
        // Tak::KD($this->users);
        /**/
    }
    
    public function actionIndex() {
        $this->render('index', array(
            'model' => null,
        ));
    }
    
    public function getLinkOrderUser($itemid) {
        $btns = array();
        $btns[] = JHtml::link(Tk::g('Order') , array(
            '/Order',
            'SubClientele[manageid]' => $itemid,
        ) , array(
            'class' => 'btn btn-small'
        ));
        $strBtns = implode('  ', $btns);
        return $strBtns;
    }
    private function getOrderUser($key = false, $mid = false) {
        $sql = "SELECT  m.* FROM  :users  AS m
        #otherTable
         AND m.itemid IN(SELECT o.manageid FROM :order AS o WHERE o.fromid=:fromid GROUP BY o.manageid) ";
        if ($mid > 0) {
            $sql = strtr($sql, array(
                'm.*' => ' m.*,sub.mid '
            ));
            $otherTable = ',' . Merchandiser::$table . " AS sub WHERE sub.mid = $mid AND sub . manageid = m . itemid AND item = 'OrderClientele'";
            $sql.= ' AND sub.fromid =:fromid ';
        } else {
            $otherTable = ' WHERE 1=1 ';
        }
        if ($key) {
            $sql.= " AND m . companyLIKE'%$key%'";
        }
        $sql.= ' ORDER BY m.company ASC';
        $sql = strtr($sql, array(
            ':order' => Order::$table,
            ':users' => Profile::$table,
            ':fromid' => Tak::getFormid() ,
            '#otherTable' => $otherTable,
        ));
        $rawData = Tak::getDb('db')->createCommand($sql)->queryAll();
        $dataProvider = new CArrayDataProvider($rawData, array(
            'id' => 'profile',
            'pagination' => array(
                'pageSize' => 10,
            ) ,
        ));
        return $dataProvider;
    }
    public function actionOrderClientele() {
        /*orderclientele*/
        $m = 'Profile';
        $model = new $m();
        $model->unsetAttributes();
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $dataProvider = $this->getOrderUser($model->company, $model->itemid);
        $this->render('orderclientele', array(
            'model' => $model,
            'tags' => $dataProvider,
        ));
    }
    public function actionLog() {
        $m = 'SubAdminLog';
        $model = new $m('search');
        $model->unsetAttributes();
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $model->setGetCU()->setUsers($this->users);
        $this->render('log', array(
            'model' => $model,
        ));
    }
    
    public function actionClienteles() {
        $m = 'SubClientele';
        $model = new $m('search');
        $model->setGetCU();
        $model->unsetAttributes();
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render('clienteles', array(
            'model' => $model,
        ));
    }
    
    public function actionSelectById($id = false) {
        $m = $this->getM();
        $_tempname = $this->modelName;
        $this->modelName = 'Sub' . $m;
        $result = parent::actionSelectById($id);
        $this->modelName = $_tempname;
    }
    
    private function getM() {
        $m = Yii::app()->request->getQuery('get', false);
        $m = strtolower($m);
        $m = ucwords($m);
        $_models = array(
            'Manage' => 1,
            'Clientele' => 2
        );
        if (!isset($_models[$m])) {
            exit;
        }
        return $m;
    }
    
    protected function getSelectOption($q, $not = false) {
        $m = $this->getM();
        $_tempname = $this->modelName;
        $this->modelName = 'Sub' . $m;
        $result = parent::getSelectOption($q, $not);
        
        $this->modelName = $_tempname;
        $criteria = $result['data']['criteria'];
        if ($m === 'Manage') {
            $result['data']['attributes'][] = 'user_nicename';
            if ($q) {
                $criteria->addSearchCondition('user_nicename', $q, true, 'OR');
            }
        }
        $result['data']['criteria'] = $criteria;
        return $result;
    }
    
    public function actionUsers($id) {
        $model = $this->loadModels($id, 'Manage');
        $subusers = new Subordinate;
        // $subusers->unsetAttributes();
        $subusers->initMos($model->attributes);
        
        $q = Yii::app()->request->getQuery('q', false);
        $data = $subusers->getNotUser($q);
        $total = count($data);
        $json = array(
            'itemCount' => 5,
            'totalItemCount' => 5,
            'currentPage' => 0,
            'pageCount' => 1,
            'pageSize' => 999,
            'data' => $data
        );
        
        $jobj = new stdclass();
        foreach ($json as $key => $value) {
            $jobj->$key = $value;
        }
        // echo json_encode($jobj);
        $this->writeData(json_encode($json));
    }
    
    public function actionUsersx($id, $page_limit = 10) {
        $model = $this->loadModels($id, 'Manage');
        $subusers = new SubUsers;
        // $subusers->unsetAttributes();
        $subusers->attributes = $model->attributes;
        $q = Yii::app()->request->getQuery('q', false);
        $contion = array(
            $subusers->getSql(true) ,
        );
        $where = implode(' AND ', $contion);
        $criteria = new CDbCriteria;
        $criteria->addCondition($where);
        if ($q) {
            $criteria->addSearchCondition('user_name', $q, 'OR');
            $criteria->addSearchCondition('user_nicename', $q, true, 'OR');
        }
        
        $dataProvider = new JSonActiveDataProvider('Manage', array(
            'attributes' => array(
                'manageid',
                'user_name',
                'user_nicename'
            ) ,
            'attributeAliases' => array(
                'manageid' => 'itemid',
                'user_name' => 'title'
            ) ,
            'sort' => array(
                'defaultOrder' => 'add_time DESC,user_nicename ASC',
            ) ,
            'pagination' => array(
                'pageSize' => 999
            ) ,
            'criteria' => $criteria
        ));
        $this->writeData($dataProvider->getJsonData());
    }
    public function actionClientelesMove() {
        $m = 'MovesForm';
        $model = new $m;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->validate()) {
                foreach (array(
                    $model->fMid,
                    $model->tMid
                ) as $value) {
                    $this->loadModels($value, 'SubManage', true);
                }
                $arr = $model->moveClienteles();
                if ($arr && count($arr) > 0) {
                    $str = '成功转移 <br />客户 <span class="red">:c</span> ,<br /> 联系人<span class="red">:cp</span>, <br />联系记录<span class="red">:cc</span>';
                    $str = strtr($str, $arr);
                    Tak::msg('', $str);
                }
            }
        }
        $this->render('clientelesmove', array(
            'model' => $model,
        ));
    }
    
    public function actionClienteleMove($id) {
        $model = $this->loadModels($id, 'SubClientele');
        $uname = $model->iManage->user_nicename;
        $uid = $model->manageid;
        $m = $this->modelName;
        
        $mF = 'MovesForm';
        $modelF = new $mF;
        $modelF->fMid = $uid;
        if (isset($_POST[$mF])) {
            $modelF->attributes = $_POST[$mF];
            if ($modelF->validate()) {
                foreach (array(
                    $modelF->fMid,
                    $modelF->tMid
                ) as $value) {
                    $this->loadModels($value, 'SubManage', true);
                }
                
                $arr = $modelF->moveClienteles($model->primaryKey);
                if ($arr && count($arr) > 0) {
                    if ($this->isAjax) {
                        exit;
                    } else {
                        $arr[':clientele_name'] = $model->clientele_name;
                        $str = '成功转移 客户 - :clientele_name <br /> 联系人<span class="red">:cp</span>, <br />联系记录<span class="red">:cc</span>';
                        $str = strtr($str, $arr);
                        Tak::msg('', $str);
                        $this->redirect(array(
                            'Clienteles'
                        ));
                    }
                }
            }
        }
        $this->render('clientelemove', array(
            'model' => $model,
            'uname' => $uname,
            'uid' => $uid,
            'modelF' => $modelF,
        ));
    }
    
    public function actionClientelesView($id) {
        $model = $this->loadModels($id, 'SubClientele');
        $strView = 'clientelesview';
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
}
