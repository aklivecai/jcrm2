<?php
class JurenModule extends CWebModule {
    public $baseUrl = '/juren';
    public $layout = 'juren.views.layouts.column2';
    public $debug = true;
    public $cssFile;
    private $_assetsUrl;
    
    public function init() {
        Yii::app()->errorHandler->errorAction = 'juren/default/error';
        Yii::app()->defaultController = 'default';
        // Tak::KD(Yii::app()->user->loginUrl);
        Yii::app()->user->loginUrl = array(
            'juren/default/login'
        );
        // Tak::KD(Yii::app()->user->loginUrl);
        Yii::app()->homeUrl = Yii::app()->createUrl('juren/default/index');
        // Tak::KD(Yii::app()->request->hostInfo );
        // Tak::KD(Yii::app()->request->baseUrl);
        // import the module-level models and components
        $this->setImport(array(
            'juren.models.*',
            'juren.components.*',
            'juren.components.*',
        ));
    }
    public function registerScripts() {
        // Get the url to the module assets
        $assetsUrl = $this->getAssetsUrl();
        // Register the necessary scripts
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($assetsUrl . '/js/load.js');
        $cs->registerCssFile($assetsUrl . '/css/core.css');
    }
    public function getAssetsUrl() {
        if ($this->_assetsUrl === null) {
            $assetsPath = Yii::getPathOfAlias('juren.assets');
            // We need to republish the assets if debug mode is enabled.
            if ($this->debug === true) $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            else $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
        }
        
        return $this->_assetsUrl;
    }
    
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else return false;
    }
}
