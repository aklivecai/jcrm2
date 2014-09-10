<?php
class WorkflowController extends Controller {
    public $listWorks = array();
    public function init() {
        $this->layout = $this->module->layout;
        $this->initData();
        parent::init();
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
    //弹窗中提示信息,关闭当前窗口
    public function msgTip($info) {
        $this->setLayoutWin();
        $script = sprintf('closeWin({url:false,info:"%s"});', $info);
        $this->render('//chip/iframe', array(
            'script' => $script,
        ));
        exit;
    }
    private function initData() {
        $tags = FlowInfo::model()->findAll('status=2');
        if (count($tags) > 0) {
            foreach ($tags as $key => $value) {
                $this->listWorks[] = array(
                    'label' => $value->flow_name,
                    'url' => array(
                        'default/postflow',
                        'id' => $value->getSId() ,
                    ) ,
                    'linkOptions' => array(
                        'class' => 'target-win',
                        'data-full' => true,
                    ) ,
                );
            }
        } else {
            $this->listWorks[] = array(
                'label' => '还没有审批单，点击新建',
                'url' => array(
                    'production/action',
                ) ,
                'linkOptions' => array(
                    'class' => 'target-win',
                    "data-width" => 320,
                    "data-height" => 180
                ) ,
            );
        }
    }

/**
 * [loadModel description]
 * @param  [type]  $id    [description]
 * @param  boolean $error [description]
 * @return [type]         [description]
 */
    public function loadModel($id, $error = true) {
        if ($this->_model === null) {
            $id = $this->getSId($id);
            $m = $this->modelName;
            $m = $m::model();
            $m = $m->findByPk($id);
            if ($m === null && $error) {
                $this->error();
            }
            $this->_model = $m;
        }
        return $this->_model;
    }
    /**
     * [loadModel description]
     * @param  [type]  $id     [description]
     * @param  boolean $m      [模块]
     * @param  boolean $isload [是否保存加载]
     * @return [type]          [返回查找的信息]
     */
    public function loadModels($id, $m = false, $isload = false, $err = false) {
        !$m && $m = $this->modelName;
        if ($isload || !isset($this->_modes[$m])) {
            $id = $this->getSId($id);
            $model = $m::model();
            $model = $model->findByPk($id);
            if ($model === null) {
                if ($err) {
                    $this->error();
                } else {
                    return null;
                }
            }
            $this->_modes[$m] = $model;
        }
        return $this->_modes[$m];
    }
    private $__assetsUrl = null;
    public function addScriptFile($arrUrl, $position = null, array $htmlOptions = array()) {
        if (!is_array($arrUrl)) {
            $arrUrl = array(
                $arrUrl
            );
        }
        if ($this->__assetsUrl === null) {
            $assetsPath = Yii::getPathOfAlias('workflow.assets');
            // We need to republish the assets if debug mode is enabled.
            if (YII_DEBUG) {
                $this->__assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            } else {
                $this->__assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
            }
        }
        $assetsUrl = $this->__assetsUrl . '/js/';
        foreach ($arrUrl as $url) {
            $url = $assetsUrl . $url;
            !strpos($url, '?') && $url.= "?";
            $url.= 't=201406028';
            Yii::app()->clientScript->registerScriptFile($url, $position, $htmlOptions);
        }
        return $this;
    }
}
