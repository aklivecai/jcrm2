<?php
class TestMemeberController extends JController {
    public $defaultAction = 'admin';
    public function init() {
        $this->modelName = 'Test9Memeber';
        parent::init();
        $this->menu = array(
            'admin' => array(
                'label' => Tk::g('Admin') ,
                'url' => array(
                    'admin'
                )
            ) ,
            'create' => array(
                'label' => Tk::g('Create') ,
                'url' => array(
                    'create'
                )
            ) ,
            'volume' => array(
                'label' => Tk::g('Volume') ,
                'url' => array(
                    'volume'
                )
            ) ,
            'import' => array(
                'label' => Tk::g('Import') ,
                'url' => array(
                    'import'
                )
            )
        );
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $m = $this->modelName;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->save()) {                
                if (isset($_POST[$m]['manageid'])) {
                    $manageid = $_POST[$m]['manageid'];
                    if ($model->manageid != $manageid) {
                        $model->moveManage($manageid);
                    }
                }
                $this->redirect($this->returnUrl ? $this->returnUrl : array(
                    'view',
                    'id' => $model->primaryKey
                ));
            }
        }
        
        $label = $model->getAttributeLabel('manageid');
        $manages = array(
            '0' => $label
        );
        foreach (Manage::model()->findAllByAttributes(array(
            "fromid" => Tak::getFormid()
        )) as $record) {
            $manages[$record->primaryKey] = $record->user_name . ' - ' . $record->user_nicename;
        }
        $this->render('update', array(
            'model' => $model,
            'manages' => $manages,
        ));
    }
    
    public function actionVolume() {
        $model = new VolumeForm();
        if (isset($_POST['VolumeForm'])) {
            $model->attributes = $_POST['VolumeForm'];
            $time = $model->save();
            if ($time > 0) {
                $this->toSTime($time);
            }
        }
        $this->render('volume', array(
            'model' => $model,
        ));
    }
    public function actionImport() {
        $m = 'ImportForm';
        $model = new $m();
        
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            $model->file = CUploadedFile::getInstance($model, "file");
            $time = $model->save();
            if ($time > 0) {
                $this->toSTime($time);
            }
        }
        $this->render('import', array(
            'model' => $model,
        ));
    }
    public function toSTime($time) {
        $this->redirect(array(
            'admin',
            'TestMemeber[add_time]' => $time
        ));
    }
    public function actionDeleteDb($id) {
        $model = $this->loadModel($id);
        Info::model()->deleteAllByAttributes(array(
            'fromid' => $model->primaryKey,
            'type' => 'dbconfig'
        ));
        TAk::deleteWdb($model->primaryKey);
        $this->redirect(array(
            'view',
            'id' => $model->primaryKey
        ));
    }
    public function actionDb($id) {
        $user = $this->loadModel($id);
        // Tak::KD(Tak::getWdb($user->primaryKey));
        $m = 'DbConfig';
        $dbconfig = Info::model()->findByAttributes(array(
            'fromid' => $user->primaryKey,
            'type' => 'dbconfig'
        ));
        if ($dbconfig) {
            $model = new $m('update');
            $model->attributes = unserialize($dbconfig['title']);
            // echo $dbconfig['title'];
            // print_r(unserialize($dbconfig['title']));
            // print_r($model->attributes);
            /**/
        } else {
            $dbconfig = new Info();
            $model = new $m('create');
        }
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->validate()) {
                $dbconfig->attributes = array(
                    'fromid' => $user->primaryKey,
                    'type' => 'dbconfig',
                    'title' => serialize($model->attributes) ,
                );
                // Tak::KD($model->attributes);
                // Tak::KD($dbconfig->attributes,1);
                if ($dbconfig->save()) {
                    // echo $user->primaryKey;
                    // 更新数据库缓存
                    TAk::setWdb($model->toString() , $user->primaryKey);
                    $this->redirect(array(
                        'db',
                        'id' => $user->primaryKey
                    ));
                }
            }
        }
        $this->render('db', array(
            'model' => $model,
            'user' => $user,
        ));
    }
}
