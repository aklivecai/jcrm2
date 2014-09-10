<?php
class TestLogController extends JController {
    public function init() {
        parent::init();
        $this->modelName = 'TestLog';
        $this->layout = 'column1';
    }
    
    public function actionCreate() {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionUpdate($id) {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionDelete($id) {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    
    public function actionIndex() {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionAdmin() {
        $model = new TestLog('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['TestLog'])) $model->attributes = $_GET['TestLog'];
        
        $this->render('admin', array(
            'model' => $model,
        ));
    }
}
