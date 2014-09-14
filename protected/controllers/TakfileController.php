<?php
class TakfileController extends RController {
    public $layout = '//layouts/columnWindows';
    public $modelName = 'TakFile';
    private $_model = null;
    public function init() {
        parent::init();
    }
    /**
     * [loadModel description]
     * @param  [type]  $id
     * @param  boolean $recycle 状态
     * @param  boolean $notcu 是否限制为当前用户
     * @return [type] $model
     */
    public function loadModel($id) {
        if ($this->_model === null) {
            $id = Tak::getSId($id);
            $m = $this->modelName;
            $m = $m::model();
            $m = $m->findByPk($id);
            if ($m === null) {
                $this->error();
            }
            $this->_model = $m;
        }
        return $this->_model;
    }
    public function error($code = 404, $msg = '所请求的页面不存在。') {
        throw new CHttpException($code, $msg);
    }
    //ajax信息返回值,1成功,0失败,info提示信息
    public function message($info, $status = true) {
        header('Content-Type: application/json');
        $tags = array(
            'status' => $status ? 1 : 0,
            'info' => $info,
        );
        echo CJSON::encode($tags);
        exit;
    }
    public function actionUpload($id, $itemid = false) {
        $id = Tak::getSId($id);
        if ($itemid) {
            $itemid = Tak::getSId($itemid);
        } else {
            $itemid = 0;
        }
        $signature = Tak::getPost('md5', false);
        if (!$signature) {
            $signature = md5(Tak::fastUuid(9) . date('Ymd-His'));
        }
        $dir = FUtils::getPathBySplitStr($signature);
        $folder = Tak::getUserDir() . $dir . '/';
        $root = YiiBase::getPathOfAlias('webroot');
        Tak::MkDirs($root . $folder);
        
        $image = CUploadedFile::getInstanceByName('file');
        if ($image == null) {
            $this->error();
        }
        $name = $folder . $signature;
        $file_name = $image->name;
        $image->saveAs($root . $name);
        
        $ext = $image->extensionName; //上传文件的扩展名
        $m = $this->modelName;
        $model = new $m('create');
        $model->attributes = array(
            'file_size' => $image->size,
            'file_signature' => $signature,
            'parent_file_id' => $id,
            'version_id' => $itemid,
            'file_path' => $name,
            'file_name' => $file_name,
            'file_type' => 0,
            'suffix' => FUtils::getSuffix($ext) ,
            'mime_type' => FUtils::mimeContentType($file_name, $root . $name) ,
            /*$image->type() 系统带的有点长*/
        );
        // Tak::l(true,sprintf('$root:%s ,file_name:%s, ',$root,$file_name));
        if ($model->validate()) {
            $model->save();
            $info = $model->getInfo();
            $status = 1;
        } else {
            $status = 0;
            $info = Tak::getMsgByErrors($model->getErrors());
        }
        $this->message($info, $status);
    }
    public function actionDownload($id) {
        $module = $this->loadModel($id);
        $root = YiiBase::getPathOfAlias('webroot');
        $file = $root . $module->file_path;
        if (file_exists($file)) {
            FUtils::outContent($file, $module->mime_type, $module->file_name);
        } else {
            $this->error(404, '无法找到文件,可能文件已经删除');
        }
    }
}
