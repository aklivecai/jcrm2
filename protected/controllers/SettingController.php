<?php
class SettingController extends Controller {
    
    public $layout = 'column2';
    public function init() {
        parent::init();
        $this->primaryName = 'itemid';
        $this->modelName = 'Setting';
    }
    public function actionCreates($id) {
        $m = $this->loadModels($id,'Manage');
        $manageid = $m->primaryKey;
        if (isset($_POST['Setting']) && is_array($_POST['Setting'])) {
            foreach ($_POST['Setting'] as $key => $value) {
                $model = new Setting;
                $model->deleteAllByAttributes(array(
                    'item_key' => $key,
                    'manageid' => $manageid,
                ));
                if ($value==''||$value<=0) {
                    continue;
                }
                $model->attributes = array(
                    'item_key' => $key,
                    'item_value' => $value,
                    'manageid' => $manageid,
                );
                $model->save();
            }
        } 
        
        $this->redirect($this->returnUrl);
    }
    public function actionCreate() {
        $model = new Setting;
        if (isset($_POST['Setting'])) {
            $model->attributes = $_POST['Setting'];
            if ($model->takSave()) {
                if (strpos($model->item_key, 'themeSettings') !== false) {
                    //如果是 配置信息即可，生效
                    Yii::app()->user->setState($model->item_key, $model->item_value);
                    exit;
                }
            } else {
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }
    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Setting');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Setting('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Setting'])) $model->attributes = $_GET['Setting'];
        
        $this->render('admin', array(
            'model' => $model,
        ));
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Setting the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Setting::model()->findByPk($id);
        if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
