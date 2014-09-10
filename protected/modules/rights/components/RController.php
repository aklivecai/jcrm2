<?php
/**
 * Rights base controller class file.
 *
 * @author Christoffer Niska <cniska@live.com>
 * @copyright Copyright &copy; 2010 Christoffer Niska
 * @since 0.6
 */
class RController extends CController {
    /**
     * @property string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @property array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @property array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    /**
     * The filter method for 'rights' access filter.
     * This filter is a wrapper of {@link CAccessControlFilter}.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     */
    public function filterRights($filterChain) {
        $filter = new RightsFilter;
        $filter->allowedActions = $this->allowedActions();
        $filter->filter($filterChain);
    }
    /**
     * @return string the actions that are always allowed separated by commas.
     */
    public function allowedActions() {
        return '';
    }
    /**
     * Denies the access of the user.
     * @param string $message the message to display to the user.
     * This method may be invoked when access check fails.
     * @throws CHttpException when called unless login is required.
     */
    public function accessDenied($message = null) {
        if ($message === null) $message = Rights::t('core', 'You are not authorized to perform this action.');

        $user = Yii::app()->getUser();
        if ($user->isGuest === true) $user->loginRequired();
        else throw new CHttpException(403, $message);
    }

    public function regScriptFile($arrUrl, $position = null, array $htmlOptions = array() , $dir = 'js') {
        if (!is_array($arrUrl)) {
            $arrUrl = array(
                $arrUrl
            );
        }
        $assetsUrl = $this->getAssetsUrl() . $dir . '/';
        foreach ($arrUrl as $url) {
            $url = $assetsUrl . $url;
            !strpos($url, '?') && $url.= "?";
            $url.= 't=201406028';
            Yii::app()->clientScript->registerScriptFile($url, $position, $htmlOptions);
        }
        return $this;
    }
    public function regCssFile($arrUrl, $media = '', $dir = 'css') {
        if (!is_array($arrUrl)) {
            $arrUrl = array(
                $arrUrl
            );
        }
        $assetsUrl = $this->getAssetsUrl() . $dir . '/';
        foreach ($arrUrl as $url) {
            $url = $assetsUrl . $url;
            Yii::app()->clientScript->registerCssFile($url, $media);
        }
        return $this;
    }

    protected $_assetsUrl = null;

    public function getAssetsUrl() {
        // return YiiBase::getPathOfAlias('webroot');
        if ($this->_assetsUrl === null) {
            if (YII_DEBUG||true) {
                $this->_assetsUrl = sprintf("%s/themes/crm2013/assets/", Yii::app()->getBaseUrl());
                // $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            } else {
                $assetsPath = YiiBase::getPathOfAlias('webroot') . '/themes/crm2013/assets/';
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
            }
        }
        return $this->_assetsUrl . '/';
    }
}
