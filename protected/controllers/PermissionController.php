<?php
class PermissionController extends Controller {
    protected $tabs = null;
    private $_authorizer = null;
    private $_mods = null;
    public $defaultAction = 'admin';
    public function init() {
        parent::init();
        $this->modelName = 'Permission';
        if (!$this->isAjax) {
            $tabs = Permission::getList();
            foreach ($tabs as $key => $value) {
                // $tabs[$this->setSId($key) ] = $value;
                // unset($tabs[$key]);
                
            }
            $this->tabs = $tabs;
            if (isset($this->tabs['998'])) {
            }
        }
    }
    public function loadModel($id) {
        if ($this->_model === null) {
            $id = $this->getSId($id);
            $m = $this->modelName;
            $m = $m::model();
            $this->_model = $m->findByPk($id);
            if ($this->_model === null) $this->error();
        }
        return $this->_model;
    }
    public function getAuthorizer() {
        if (isset($this->_authorizer) === false) {
            $this->_authorizer = Jurisdiction::getAuthorizer();
        }
        return $this->_authorizer;
    }
    public function getItemName() {
        return isset($_GET['name']) === true ? urldecode($_GET['name']) : null;
    }
    
    public function loadModelI($name) {
        if ($this->_mods === null) {
            $name = $this->getSId($name);
            $itemName = $name;
            if ($itemName !== null) {
                $authorizer = $this->getAuthorizer();
                $this->_mods = $authorizer->authManager->getAuthItem($itemName);
                $this->_mods = $authorizer->attachAuthItemBehavior($this->_mods);
            }
            if ($this->_mods === null) $this->error();
        }
        return $this->_mods;
    }
    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        if (!isset($_GET['ajax'])) $this->redirect(isset($this->returnUrl) ? $this->returnUrl : array(
            'admin'
        ));
    }
    
    public function actionAdmin() {
        $len = count($this->tabs);
        if ($len == 0) {
            $this->redirect(array(
                'create'
            ));
        } else {
            $t = array_keys($this->tabs);
            $id = $t[0];
            $this->redirect(array(
                'view',
                'id' => $this->setSId($id) ,
            ));
        }
    }
    
    public function actionPreview($id) {
        $model = $this->loadModel($id);
        $childDataProvider = $model->getChild();
        $data = $childDataProvider->getData();
        $this->render($this->templates['preview'], array(
            'model' => $model,
            'data' => $data,
        ));
    }
    
    public function actionShow($child) {
        $child = urldecode($child);
        $id = Tak::decrypt($child);
        $itemName = $id;
        $_model = $this->loadModel($id);
        $model = $this->loadModelI($id);
        $itemName = $id;
        $type = Rights::getValidChildTypes($model->type);
        $exclude = array(
            Rights::module()->superuserName
        );
        $childSelectOptions = Rights::getParentAuthItemSelectOptions($model, $type, $exclude);
        
        $parentDataProvider = new RAuthItemParentDataProvider($model);
        $childDataProvider = new RAuthItemChildDataProvider($model);
        
        $this->render('show', array(
            'model' => $_model,
            'models' => $model,
            'id' => $id,
            'childSelectOptions' => $childSelectOptions,
            'parentDataProvider' => $parentDataProvider,
            'childDataProvider' => $childDataProvider,
        ));
    }
    
    public function actionView($id) {
        $_model = $this->loadModel($id);
        $model = $this->loadModelI($id);
        $itemName = $this->getSId($id);
        
        $type = Rights::getValidChildTypes($model->type);
        $exclude = array(
            Rights::module()->superuserName
        );
        $childSelectOptions = Rights::getParentAuthItemSelectOptions($model, $type, $exclude);
        
        if ($childSelectOptions !== array()) {
            $childFormModel = new AuthChildForm();
            // Child form is submitted and data is valid
            if (isset($_POST['AuthChildForm']) === true) {
                $childFormModel->attributes = $_POST['AuthChildForm'];
                if ($childFormModel->validate() === true) {
                    $authorizer = $this->getAuthorizer();
                    
                    $childFormModel->itemname = Tak::decrypt($childFormModel->itemname);
                    $authorizer->authManager->addItemChild($itemName, $childFormModel->itemname);
                    $child = $authorizer->authManager->getAuthItem($childFormModel->itemname);
                    $child = $authorizer->attachAuthItemBehavior($child);
                    // Set a flash message for adding the child
                    Tak::setFlash(Rights::t('core', 'Child :name added.', array(
                        ':name' => $child->getNameText()
                    )) , 'success');
                    $this->redirect(array(
                        'view',
                        'id' => $id,
                    ));
                }
            } else {
            }
        } else {
            $childFormModel = null;
        }
        // 取消部门选择
        if (isset($childSelectOptions['部门']) === true) {
            unset($childSelectOptions['部门']);
        }
        
        $_notArr = array(
            'Site.*',
            'Setting.*',
            'PostUpdateOwn',
            'Site.Logout',
            'Manage.Select',
            'AddressBook.View',
            'AddressBook.Index',
            'Clientele.Index',
            'ContactpPrson.*',
            'Contact.*',
            'Subordinate.*',
            'Events.*',
            'Message.*',
            'Warehouse',
        );
        $notstr = '~' . implode('~', $_notArr) . '~';
        // Tak::KD($notstr,1);
        foreach ($childSelectOptions as $key => $value) {
            if ($key == '部门') {
                unset($childSelectOptions[$key]);
            } else {
                $t = array();
                foreach ($value as $k1 => $v1) {
                    if (strpos($notstr, '~' . $k1 . '~') !== false) {
                        unset($childSelectOptions[$key][$k1]);
                    } else {
                        $t[Tak::encrypt($k1) ] = $v1;
                    }
                }
                $childSelectOptions[$key] = $t;
            }
        }
        $parentDataProvider = new RAuthItemParentDataProvider($_model);
        $childDataProvider = new RAuthItemChildDataProvider($_model);
        $childDataProvider = $_model->getChild();
        $data = $childDataProvider->getData();
        
        $this->render($this->templates['view'], array(
            'model' => $_model,
            'models' => $model,
            'id' => $id,
            'childFormModel' => $childFormModel,
            'childSelectOptions' => $childSelectOptions,
            'data' => $data,
        ));
    }
    
    public function actionRemoveChild($id, $child) {
        $_model = $this->loadModel($id);
        $itemName = $this->getSId($id);
        $childName = Tak::decrypt($child);
        
        $authorizer = $this->getAuthorizer();
        // Remove the child and load it
        $authorizer->authManager->removeItemChild($itemName, $childName);
        $child = $authorizer->authManager->getAuthItem($childName);
        $child = $authorizer->attachAuthItemBehavior($child);
        // Set a flash message for removing the child
        Tak::setFlash(Rights::t('core', 'Child :name removed.', array(
            ':name' => $child->getNameText()
        )) , 'success');
        // If AJAX request, we should not redirect the browser
        if (!$this->isAjax) {
            $this->redirect(array(
                'view',
                'id' => urlencode($id)
            ));
        } else {
        }
    }
}
