<?php
class Contact531Controller extends JController {
    public $defaultAction = 'admin';
    public function init() {
        $this->modelName = 'Contact531';
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
    
    public function actionImport() {
        $m = 'ImportForm531';
        $model = new $m();
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            $file = Ak::getState('_file', false);
            if ($file) {
                $time = $model->import($file);
            } else {
                $model->file = CUploadedFile::getInstance($model, "file");
                $time = $model->save();
            }
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
            'Contact531[add_time]' => $time
        ));
    }
    
    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        if (!isset($_GET['ajax'])) $this->redirect(isset($this->returnUrl) ? $this->returnUrl : array(
            'admin'
        ));
    }
    
    public function actionToClientele($id) {
        $model = $this->loadModel($id);
        $clientele = new Clientele('create');
        $clientele->attributes = array(
            'annual_revenue' => 0,
            'industry' => 0,
            'clientele_name' => $model->clientele_name,
            'address' => $model->clientele_name,
            'telephone' => $model->phone,
            'web' => $model->web,
            'note' => sprintf("%s\n%s", $model->note, $model->business) ,
            'add_time' => $model->add_time,
            'add_ip' => $model->add_ip,
            'modified_time' => Tak::now() ,
        );
        if ($clientele->validate()) {
            $clientele->save();
            $contactpprson = new ContactpPrson('create');
            $contactpprson->attributes = array(
                'nicename' => $model->nicename,
                'clienteleid' => $clientele->primaryKey,
                'phone' => $model->phone,
                'mobile' => $model->mobile,
            );
            $contactpprson->save();
            $model->delete();
            $url = Yii::app()->createUrl('/Contact/Create/', array(
                'Contact[clienteleid]' => $clientele->primaryKey,
                'Contact[prsonid]' => $contactpprson->primaryKey,
                'Contact[contact_time]' => $contactpprson->add_time,
                'Contact[note]' => '531'
            ));
            $this->redirect($url);
        } else {
            $msg = Tak::getMsgByErrors($clientele->getErrors());
            Yii::app()->user->setFlash('msg', $msg);
            $this->redirect('admin');
        }
    }
    /**
     * 检测ID下公司是不是已经存在
     * @param  int $id [description]
     * @return [type]     [description]
     */
    public function actionCheck($id) {
        $model = $this->loadModel($id);
        $clientele = Clientele::model()->setGetCU()->findByAttributes(array(
            'clientele_name' => $model->clientele_name
        ));
        $this->render('check', array(
            'clientele' => $clientele,
            'model' => $model,
        ));
    }
}
