<?php
class Tak extends Ak {
    public static function giiColAdmin($col) {
        $result = self::giiCol($col);
        if (!$result) {
            $result = strpos('::,status,display,modified_time,', ",$col,") > 0;
        }
        return $result;
    }
    public static function giiColNot($col) {
        $result = self::giiCol($col);
        if (!$result) {
            $result = strpos('::,add_time,modified_time,last_time,', ",$col,") > 0;
        }
        return $result;
    }
    
    public static function giiCol($col) {
        $result = strpos('::,itemid,fromid,manageid,add_us,add_ip,modified_us,modified_ip,', ",$col,") > 0;
        return $result;
    }
    
    public static function getEditMenu($itemid, $isNewRecord = true) {
        $items = array(
            'Save' => array(
                'icon' => 'isw-edit',
                'url' => 'javascript:;',
                'label' => Tk::g('Save') ,
                'linkOptions' => array(
                    'class' => 'save',
                    'submit' => array()
                ) ,
            )
        );
        if ($isNewRecord) {
            $action = 'Create';
        } else {
            $action = 'Update';
            $items['View'] = array(
                'icon' => 'isw-zoom',
                'url' => array(
                    'view',
                    'id' => $itemid
                ) ,
                'label' => Tk::g('View') ,
            );
            $items['Create'] = array(
                'icon' => 'isw-plus',
                'url' => array(
                    'create'
                ) ,
                'label' => Tk::g('Create New') ,
            );
            $items['Delete'] = array(
                'icon' => 'isw-delete',
                'url' => array(
                    'delete',
                    'id' => $itemid
                ) ,
                'label' => Tk::g('Delete') ,
                'linkOptions' => array(
                    'class' => 'delete'
                ) ,
            );
        }
        array_push($items, array(
            'icon' => 'isw-refresh',
            'url' => Yii::app()->request->url,
            'label' => Tk::g('Refresh') ,
        ) , array(
            'icon' => 'isw-left',
            'url' => '' . Yii::app()->request->urlReferrer,
            'label' => Tk::g('Return') ,
        ));
        return $items;
    }
    
    public static function getViewMenu($itemid) {
        $itemid = self::setSId($itemid);
        $items = array(
            'Action' => array(
                'label' => Tk::g('Action') ,
                'icon' => 'fire',
                'url' => '',
                'active' => true
            ) ,
            'View' => array(
                'label' => Tk::g('View') ,
                'icon' => 'eye-open'
            ) ,
            'Admin' => array(
                'label' => Tk::g('Admin') ,
                'icon' => 'th',
                'url' => array(
                    'admin'
                )
            ) ,
            'Create' => array(
                'label' => Tk::g('Create') ,
                'icon' => 'pencil',
                'url' => array(
                    'create'
                )
            ) ,
            'Update' => array(
                'label' => Tk::g('Update') ,
                'icon' => 'edit',
                'url' => array(
                    'update',
                    'id' => $itemid
                )
            ) ,
            '---',
            'Delete' => array(
                'label' => Tk::g('Delete') ,
                'icon' => 'trash',
                'url' => array(
                    'delete',
                    'id' => $itemid
                ) ,
                'linkOptions' => array(
                    'class' => 'delete'
                )
            ) ,
        );
        return $items;
    }
    
    public static function getListMenu() {
        $listMenu = array(
            'Create' => array(
                'icon' => 'isw-plus',
                'url' => array(
                    'create'
                ) ,
                'label' => Tk::g('Create') ,
            )
        );
        if (self::checkSuperuser()) {
            $listMenu['Recycle'] = array(
                'icon' => 'isw-delete',
                'url' => array(
                    'recycle'
                ) ,
                'label' => Tk::g('Recycle') ,
            );
        }
        /* ,array(
              'icon' =>'isw-edit',
              'url' => '#',
              'label'=>Tk::g('Update'),
              'linkOptions'=>array('class'=>'edit'),
            )
            ,array(
              'icon' =>'isw-delete',
              'url' => '#',
              'label'=>Tk::g('Delete'),
              'linkOptions'=>array('class'=>'delete-select','submit'=>array('click'=>"$.fn.yiiGridView.update('menu-grid');")),
            )*/
        
        $listMenu['Refresh'] = array(
            'icon' => 'isw-refresh',
            'url' => Yii::app()->request->url,
            'label' => Tk::g('Refresh') ,
            'linkOptions' => array(
                'class' => 'refresh'
            ) ,
        );
        
        return $listMenu;
    }
    public static function getFileTypeId($file) {
        $result = 0;
        $type = $file;
        if (strpos($file, '.') > 0) {
            $type = explode(".", $file);
            $count = count($type) - 1;
            $type = $type[$count];
        }
        $type = strtolower($type);
        switch ($type) {
            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'png':
            case 'bmp':
            case 'cdr':
            case 'psd':
            case 'ai':
                $result = 1;
            break;
            case 'rar':
            case 'zip':
            case '7z':
            case 'iso':
            case 'jar':
                $result = 2;
            break;
            case 'doc':
            case 'docx':
                $result = 3;
            break;
            case 'xls':
            case 'xlsx':
                $result = 4;
            break;
            case 'txt':
                $result = 5;
            break;
            default:
                $result = 0;
            break;
        }
        return $result;
    }
    
    public static function getFileSrc($str, $isWrite = '', $dir = '') {
        $path_info = pathinfo($str);
        $extension = $path_info['extension'];
        $file1 = md5($str);
        $file2 = self::getPathBySplitStr($file1);
        
        if ($dir = '') {
            $dir = Yii::app()->params['uploadUser'];
        }
        $dir = str_replace($file1, '', $file2);
        self::MkDirs($dir);
        $file2 = $dir . $file1;
        if ($extension) {
            $file2.= '.' . $extension;
        }
        
        self::KD($str);
        self::KD($file1);
        self::KD($file2, 1);
        if ($isWrite || true) {
            file_put_contents($file2, file_get_contents($str, FILE_USE_INCLUDE_PATH));
        }
        
        return $file2;
    }
    
    public static function getFileIco($typeid) {
        // 2压缩包,3是文档
        $arr = TakType::items('filetype');
        $result = isset($arr[$typeid]) ? $arr[$typeid] : current($arr);
        $result = Yii::app()->getBaseUrl() . '/upload/ui/ico/' . $result . '.png';
        return $result;
    }
    
    public static function get() {
        $auth = Yii::app()->authManager;
    }
    //左栏菜单
    public static function getMainMenu() {
        $controlName = Yii::app()->getController()->id;
        $strSpan = '<span class="text">%s</span>';
        $arr = array(
            'manage' => 'manage,permission,',
            'addressbook' => ',AddressBook,AddressGroups,',
            'events' => 'events',
            'clientele' => ',clientele,contactpPrson,contact,clienteles,',
            'pss' => ',purchase,stocks,product,sell,category,',
            'subordinate' => ',subordinate,',
            'production' => ',production,cost,',
            'wage' => ',wage,department,',
            'setting' => ',changepwd,profile,sell,',
            'workflow' => ',production,formproduct,',
        );
        $items = array(
            'juren' => array(
                'icon' => 'isw-grid',
                'url' => array(
                    '/juren'
                ) ,
                'label' => sprintf($strSpan, Tk::g('內部管理')) ,
                'visible' => self::getFormid() == 1,
            ) ,
            'index' => array(
                'icon' => 'isw-grid',
                'url' => array(
                    '/Site/Index'
                ) ,
                'label' => sprintf($strSpan, Tk::g('主页')) ,
            ) ,
            'setting' => array(
                'icon' => 'isw-sync',
                'label' => sprintf($strSpan, Tk::g('Setting')) ,
                'items' => array(
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('Changepwd')) ,
                        'url' => array(
                            '/Site/Changepwd'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('Profile')) ,
                        'url' => array(
                            '/Site/Profile'
                        ) ,
                        'visible' => true,
                    ) ,
                )
            ) ,
            'subordinate' => array(
                'icon' => 'isw-user',
                'url' => array(
                    '/Subordinate/'
                ) ,
                'label' => sprintf($strSpan, Tk::g(array(
                    'Subordinate',
                    'Admin'
                ))) ,
                'visible' => self::getState('isbranch', false) || self::getAdmin() ,
                'items' => array(
                    array(
                        'icon' => 'user',
                        'label' => sprintf($strSpan, Tk::g('Subordinate')) ,
                        'url' => array(
                            '/Subordinate/'
                        ) ,
                        'visible' => self::getAdmin() ,
                    ) ,
                    array(
                        'icon' => 'retweet',
                        'label' => sprintf($strSpan, Tk::g('Clienteles')) ,
                        'url' => array(
                            '/Subordinate/Clienteles'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'retweet',
                        'label' => sprintf($strSpan, Tk::g('订单客户分配')) ,
                        'url' => array(
                            '/Subordinate/OrderClientele'
                        ) ,
                        'visible' => self::getAdmin() ,
                    ) ,
                    array(
                        'icon' => 'list-alt',
                        'label' => sprintf($strSpan, Tk::g('Log')) ,
                        'url' => array(
                            '/Subordinate/Log'
                        ) ,
                        'visible' => self::getAdmin() ,
                    ) ,
                )
            ) ,
            'manage' => array(
                'icon' => 'isw-users',
                'label' => sprintf($strSpan, Tk::g(array(
                    'Manage',
                    'Admin'
                ))) ,
                'url' => array(
                    '/Manage/Admin'
                ) ,
                'visible' => self::checkAccess('Manage.*') ,
                'items' => array(
                    array(
                        'icon' => 'user',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Permissions',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Rights/Assignment/View'
                        ) ,
                        'visible' => self::checkSuperuser() && YII_DEBUG,
                        'linkOptions' => array(
                            'id' => 'tak-permissions'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'user',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Permissions',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Permission/Admin'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'plus',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Create',
                            'Manage'
                        ))) ,
                        'url' => array(
                            '/Manage/Create'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Manage',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Manage/Admin'
                        ) ,
                    )
                )
            ) ,
            'clientele' => array(
                'icon' => 'isw-bookmark',
                'label' => sprintf($strSpan, Tk::g('我的客户')) ,
                'url' => array(
                    '/Clientele/Admin'
                ) ,
                'visible' => self::checkAccess('Clientele.*') ,
                'items' => array(
                    
                    'iClientele' => array(
                        'icon' => 'screenshot',
                        'url' => array(
                            '/Import/Clientele'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Import',
                            'Clientele'
                        ))) ,
                        'visible' => self::checkAccess('Import.Clientele') ,
                    ) ,
                    array(
                        'icon' => 'plus',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Clientele',
                            'Create'
                        ))) ,
                        'url' => array(
                            '/Clientele/Create'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Clientele',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Clientele/Admin'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('ContactpPrson')) ,
                        'url' => array(
                            '/ContactpPrson/Admin'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('Contact')) ,
                        'url' => array(
                            '/Contact/AdminGroup'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('所有客户')) ,
                        'url' => array(
                            '/Clienteles/'
                        ) ,
                        'visible' => self::checkAccess('Clienteles.*')
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Clientele',
                            'Move'
                        ))) ,
                        'url' => array(
                            '/Moves/Clienteles'
                        ) ,
                        'visible' => self::checkSuperuser()
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('公海')) ,
                        'url' => array(
                            '/Clientele/Seas'
                        ) ,
                        'visible' => self::checkAccess('Clientele.*')
                    ) ,
                    array(
                        'icon' => 'trash',
                        'label' => sprintf($strSpan, Tk::g('Recycle')) ,
                        'url' => array(
                            '/Clientele/Recycle'
                        ) ,
                        'visible' => self::checkSuperuser()
                    ) ,
                ) ,
            ) ,
            'addressbook' => array(
                'icon' => 'isw-archive',
                'label' => sprintf($strSpan, Tk::g('AddressBook')) ,
                'visible' => self::checkAccess('Addressbook.Index') || self::checkAccess('Addressbook') ,
                'items' => array(
                    'iaddressbook' => array(
                        'icon' => 'screenshot',
                        'url' => array(
                            '/Import/AddressBook'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Import',
                            'AddressBook'
                        ))) ,
                        'visible' => self::checkAccess('Import.Addressbook') ,
                    ) ,
                    array(
                        'icon' => 'plus',
                        'label' => sprintf($strSpan, Tk::g('AddressGroups')) ,
                        'url' => array(
                            '/AddressGroups/Admin'
                        ) ,
                        'visible' => self::checkAccess('Addressgroups.*') ,
                    ) ,
                    array(
                        'icon' => 'plus',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Create',
                            'AddressBook'
                        ))) ,
                        'url' => array(
                            '/AddressBook/Create'
                        ) ,
                        'visible' => self::checkAccess('Addressbook.*') ,
                    ) ,
                    array(
                        'icon' => 'th-list',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Admin',
                            'AddressBook'
                        ))) ,
                        'url' => array(
                            '/AddressBook/Admin'
                        ) ,
                        'visible' => self::checkAccess('Addressbook.*') ,
                    ) ,
                    array(
                        'icon' => 'th-list',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'View',
                            'AddressBook'
                        ))) ,
                        'url' => array(
                            '/AddressBook/Index'
                        ) ,
                        'visible' => self::checkAccess('AddressBook.Index') ,
                    ) ,
                ) ,
            ) ,
            'workflow' => array(
                'visible' => self::checkAccess('Workflow.*') ,
                'icon' => 'isw-list',
                'label' => sprintf($strSpan, Tk::g('Workflow')) ,
                'url' => array(
                    '/Workflow'
                ) ,
            ) ,
            'pss' => array(
                'visible' => (self::checkAccess('Stocks.Index') || self::checkAccess('Pss.*') || self::checkAccess('Product.*') || self::checkAccess('Purchase.*') || self::checkAccess('Product.*') || self::checkAccess('Sell.*') || self::checkAccess('Stocks.*')) ,
                'icon' => 'isw-list',
                'label' => sprintf($strSpan, Tk::g(array(
                    'Admin',
                    '库存'
                ))) ,
                'url' => array(
                    '/Pss/Index'
                ) ,
                'items' => array(
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Warehouse',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Warehouse/Admin'
                        ) ,
                        'visible' => self::checkAccess('Warehouse.*') ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('Product Type')) ,
                        'url' => array(
                            '/Category/Admin?m=product'
                        ) ,
                        'visible' => self::checkAccess('Category.*') ,
                    ) ,
                    'product' => array(
                        'icon' => 'screenshot',
                        'url' => array(
                            '/Import/Product'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Import',
                            'Product'
                        ))) ,
                        'visible' => self::checkAccess('Import.Product') ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('Product')) ,
                        'url' => array(
                            '/Product/Admin'
                        ) ,
                        'visible' => self::checkAccess('Product.*') ,
                    ) ,
                    
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('入库录入')) ,
                        'url' => array(
                            '/Purchase/Admin'
                        ) ,
                        'visible' => self::checkAccess('Purchase.*') ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('出库录入')) ,
                        'url' => array(
                            '/Sell/Admin'
                        ) ,
                        'visible' => self::checkAccess('Sell.*') ,
                    ) ,
                    array(
                        'icon' => 'th',
                        'label' => sprintf($strSpan, Tk::g('Stocks')) ,
                        'url' => array(
                            '/Stocks'
                        ) ,
                        'visible' => self::checkAccess('Stocks.Index') || self::checkAccess('Stocks.*') ,
                    ) ,
                ) ,
            ) ,
            'order' => array(
                'visible' => self::checkAccess('Order.*') ,
                'icon' => 'isw-list',
                'label' => sprintf($strSpan, Tk::g(array(
                    'Order',
                    'Admin'
                ))) ,
                'url' => array(
                    '/Order/Index'
                ) ,
                'items' => array(
                    
                    array(
                        'icon' => 'shopping-cart',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Order',
                            'Config'
                        ))) ,
                        'url' => array(
                            '/OrderConfig/Config'
                        ) ,
                        'visible' => self::checkAccess('Orderconfig.*') ,
                    ) ,
                    'iorder' => array(
                        'icon' => 'screenshot',
                        'url' => array(
                            '/Import/Order'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Import',
                            'Order'
                        ))) ,
                        'visible' => self::checkAccess('Import.Order') && (self::getFormid() == 1 || self::getFormid() == 5139) ,
                    ) ,
                    array(
                        'icon' => 'shopping-cart',
                        'label' => '<span class="text">' . Tk::g(array(
                            'Order',
                            'Admin'
                        )) . '</span>',
                        'label' => sprintf($strSpan, Tk::g('Order')) ,
                        'url' => array(
                            '/Order/Admin'
                        )
                    ) ,
                    array(
                        'icon' => 'pencil',
                        'label' => sprintf($strSpan, Tk::g('自助下单')) ,
                        'url' => sprintf("%s/order/cart/%s", Yii::app()->params['userUrl'], self::getFormid()) ,
                        'linkOptions' => array(
                            'target' => '_blank'
                        )
                    ) ,
                ) ,
            ) ,
            'production' => array(
                'visible' => self::isCost() && self::checkAccess('Production.*') ,
                'icon' => 'isw-list',
                'label' => sprintf($strSpan, Tk::g(array(
                    'Production',
                    'Admin'
                ))) ,
                'url' => array(
                    '/Production/Index'
                ) ,
                'items' => array(
                    'Workshop' => array(
                        'icon' => 'certificate',
                        'url' => array(
                            '/Cost/Workshop'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Workshop',
                            'Setting'
                        ))) ,
                        'linkOptions' => array(
                            'class' => 'target-win',
                            'target' => '_blank',
                        ) ,
                    ) ,
                    array(
                        'icon' => 'th-list',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Production',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Production/Index'
                        )
                    ) ,
                    'Cost' => array(
                        'icon' => 'th-list',
                        'url' => array(
                            '/cost/Index'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g('成本核算')) ,
                    ) ,
                    
                    'CostProduct' => array(
                        'icon' => 'th-list',
                        'url' => array(
                            '/cost/Product'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g('产品成本核算')) ,
                    ) ,
                ) ,
            ) ,
            'wage' => array(
                'visible' => self::isWage() && self::checkAccess('Wage.*') ,
                'icon' => 'isw-list',
                'label' => sprintf($strSpan, Tk::g(array(
                    '计件',
                    'Wage'
                ))) ,
                'url' => array(
                    '/Wage/Index'
                ) ,
                'items' => array(
                    'Workshop' => array(
                        'icon' => 'certificate',
                        'url' => array(
                            '/Department/Admin'
                        ) ,
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Workshop',
                            'Setting'
                        ))) ,
                    ) ,
                    array(
                        'icon' => 'th-list',
                        'label' => sprintf($strSpan, Tk::g(array(
                            'Wage',
                            'Admin'
                        ))) ,
                        'url' => array(
                            '/Wage/Index'
                        )
                    ) ,
                ) ,
            ) ,
            'events' => array(
                // 'visible'=> self::checkAccess('Events.*'),
                'icon' => 'isw-calendar',
                'label' => sprintf($strSpan, Tk::g(array(
                    'Events',
                    '事项'
                ))) ,
                'url' => array(
                    '/events/index'
                ) ,
                'items' => array(
                    array(
                        'icon' => 'th-list',
                        'label' => '<span class="text">' . Tk::g(array(
                            'Events',
                            'Admin'
                        )) . '</span>',
                        'url' => array(
                            '/Events/Admin'
                        )
                    ) ,
                    array(
                        'icon' => 'plus',
                        'label' => '<span class="text">' . Tk::g('Events') . '事项</span>',
                        'url' => array(
                            '/Events/Create'
                        ) ,
                    ) ,
                ) ,
            )
        );
        // unset($items['events']);
        
        $items[] = array(
            'icon' => 'isw-zoom',
            'label' => '<span class="text">帮助中心</span>',
            'url' => array(
                '/Site/Help'
            ) ,
        );
        $items[] = array(
            'icon' => 'isw-chat',
            'label' => '<span class="text">系统其他功能</span>',
            'url' => Yii::app()->getBaseUrl() . '/upload/functionality.jpg',
            'linkOptions' => array(
                'target' => '_blank'
            )
        );
        
        $items[] = array(
            'icon' => 'isw-target',
            'label' => '<span class="text">客户案例</span>',
            'url' => 'http://www.9juren.net/',
            'linkOptions' => array(
                'target' => '_blank'
            )
        );
        
        $controlName = Yii::app()->getController()->id;
        $controlName = strtolower($controlName);
        
        if (self::checkSuperuser()) {
            $items[] = array(
                'icon' => 'isw-settings',
                'label' => '<span class="text">管理中心</span>',
                'items' => array(
                    // array('icon'=>'wrench','label'=>'<span class="text">网站设置</span>', 'url'=>array('/settin/index')),
                    array(
                        'icon' => 'list-alt',
                        'label' => '<span class="text">网站日志</span>',
                        'url' => array(
                            '/AdminLog/Admin'
                        ) ,
                    ) ,
                    array(
                        'icon' => 'fire',
                        'label' => '<span class="text">网站备份</span>',
                        'url' => array(
                            '/Site/Database'
                        ) ,
                        'visible' => YII_DEBUG
                    ) ,
                ) ,
            );
            $items[] = array(
                'icon' => 'isw-calc',
                'label' => '<span class="text">具人同行商务中心</span>',
                'url' => 'http://www.9juren.com/member/',
                'linkOptions' => array(
                    'target' => '_blank'
                )
            );
        }
        // Tak::KD(Yii::app()->getController(),1);
        // Tak::KD(Yii::app(),1);
        // Tak::KD($controlName,1);
        $tname = '';
        $_m = Yii::app()->getController()->getModule();
        if ($_m && $_m->id == 'workflow') {
            $tname = 'workflow';
        } else {
            foreach ($arr as $key => $value) {
                $key = strtolower($key);
                $value = strtolower($value);
                if ($controlName == $key || strpos($value, ",$controlName,") !== false) {
                    $tname = $key;
                    break;
                }
            }
            if ($tname == '' && $controlName == 'category' && isset($_GET['m'])) {
                if ($_GET['m'] = 'product') {
                    $tname = 'pss';
                }
            }
        }
        if (isset($items[$tname])) {
            $items[$tname]['active'] = true;
        }
        return $items;
    }
    
    public static function isRecycle() {
        $result = Yii::app()->getController()->getAction()->id == 'recycle';
        return $result;
    }
    
    public static function getAdminPageCol($arr = false, $gid = 'list-grid', $width = '60px') {
        
        $items = array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => CHtml::dropDownList('pageSize', Yii::app()->user->getState('pageSize') , TakType::items('pageSize') , array(
                'onchange' => "$.fn.yiiGridView.update('" . $gid . "',{data:{setPageSize: $(this).val()}})",
                'style' => 'width: ' . $width . ' !important',
            ))
        );
        if (is_array($arr)) {
            // self::KD($arr);
            $items = array_merge_recursive($items, $arr);
        }
        // '.Tk::g('Recycle').'
        // self::KD($control->id);
        if (self::isRecycle()) {
            $newItems = array(
                'template' => '{restore} | {del}',
                'buttons' => array(
                    'restore' => array(
                        'label' => '',
                        'url' => 'Yii::app()->controller->createUrl("restore", array("id"=>$data->primaryKey))',
                        'options' => array(
                            'title' => '还原',
                            'class' => 'icon-repeat'
                        ) ,
                    ) ,
                    'del' => array(
                        'label' => '',
                        'url' => 'Yii::app()->controller->createUrl("del", array("id"=>$data->primaryKey))',
                        'options' => array(
                            'title' => '彻底删除',
                            'class' => 'icon-remove'
                        ) ,
                    ) ,
                ) ,
            );
            // 'imageUrl'=>$this->{$id.'ButtonImageUrl'},
            $items = array_merge_recursive($items, $newItems);
        }
        return $items;
    }
    
    public static function getTakTypes() {
        $arr = array();
        $arr['product'] = array(
            'name' => 'Product',
            'file' => 'product',
            'type' => 'product',
        );
        
        return $arr;
    }
    
    public static function msg($msg, $title = '', $type = 'palert') {
        Yii::app()->clientScript->registerScript('bodyend', "show_stack('$title','$msg','$type')");
    }
    
    public static function copyright() {
        $arr = array(
            '<div class="hide">'
        );
        $arr[] = Yii::app()->params['copyright'];
        $arr[].= '<script type="text/javascript">
            var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
            document.write(unescape("%3Cscript src=\'" + _bdhmProtocol + "hm.baidu.com/h.js%3Fd98411661088365a052727ec01efb9d8\' type=\'text/javascript\'%3E%3C/script%3E"));
        </script></div>';
        echo implode($arr);
    }
    
    public static function gredViewOptions($btnColumn = true) {
        $arr = array(
            'type' => 'striped bordered condensed',
            'id' => 'list-grid',
            'dataProvider' => null,
            'enableHistory' => true,
            'afterAjaxUpdate' => 'kloadCGridview',
            'loadingCssClass' => 'grid-view-loading',
            'summaryCssClass' => 'dataTables_info',
            'pagerCssClass' => 'pagination dataTables_paginate',
            'template' => '{pager}{summary}<div class="dr"><span></span></div>{items}{pager}',
            'ajaxUpdate' => true, //禁用AJAX
            'enableSorting' => true,
            'selectableRows' => false,
            'summaryText' => '共 <span class="badge">{pages}</span> 页,当前 <span class="badge badge-success">{page}</span> 页,总数 <span class="badge badge-info">{count}</span> ',
            'pager' => array(
                'header' => '',
                'maxButtonCount' => '5',
                'hiddenPageCssClass' => 'disabled',
                'selectedPageCssClass' => 'active disabled',
                'htmlOptions' => array(
                    'class' => ''
                )
            ) ,
            'columns' => array()
        );
        if ($btnColumn) {
            $arr['columns'] = self::getAdminPageCol();
        }
        return $arr;
    }
    
    public static function submitButton($label = 'submit', $htmlOptions = array()) {
        $htmlOptions['type'] = 'submit';
        $class = 'btn';
        if (isset($htmlOptions['class'])) {
            $class.= ' ' . $htmlOptions['class'];
        }
        $htmlOptions['class'] = $class;
        
        return CHtml::tag('button', $htmlOptions, $label);
    }
    
    public static function writeInfo($key = 'source', $defaultValue = null) {
        $result = '';
        $html = self::getFlash($key, $defaultValue, true);
        if ($html) {
            $result = CHtml::tag('div', array(
                'class' => 'alert alert-block alert-success'
            ) , $html);
        }
        return $result;
    }
    
    public static function getNP($nps, $view = 'view') {
        $result = array();
        if (!is_array($nps)) {
            return false;
        }
        foreach ($nps as $key => $value) {
            $result[$key] = array(
                'label' => Tk::g($key) ,
                'url' => array(
                    $view,
                    'id' => self::setSId($value) ,
                ) ,
                'icon' => 'chevron-right',
                'linkOptions' => array(
                    'class' => 'ajax-content'
                )
            );
        }
        return $result;
    }
    
    public static function showMsg() {
        $result = self::getFlashes();
        if ($result) {
            foreach ($result as $key => $value) {
                self::msg($value, '', $key);
            }
        }
    }
    
    public static function tagNum($text, $type = '') {
        $badges = array(
            'badge'
        );
        if ($type) {
            $badges[] = $type;
        }
        return CHtml::tag('span', array(
            'class' => implode(' ', $badges)
        ) , $text);
    }
    
    public static function creaetPreviewUrl($data) {
        $result = array();
        $url = 'Preview';
        if (!is_array($data)) {
            $result['id'] = $data;
            $result = Yii::app()->getController()->createUrl($url, $result);
        } else {
            $result['id'] = $data['id'];
            isset($data['status']) && $result['status'] = K;
            isset($data['not']) && $result['not'] = K;
            if (isset($data['url'])) {
                $url = strpos($data['url'], '/') > 0 ? $data['url'] : sprintf("/%s/%s", $data['url'], $url);
            }
            if (count($result) > 1) {
                $result['uuid'] = self::setEid($result['id']);
            }
            $result = Yii::app()->createUrl($url, $result);
        }
        return $result;
    }
    
    public static function createMUrl($arr, $module = false) {
        $urls = array();
        foreach ($arr as $key => $value) {
            if ($module) {
                $url = sprintf('%s[%s]', $module, $key);
            } else {
                $url = $key;
            }
            $url.= sprintf('=%s', $value);
            $urls[] = $url;
        }
        return implode('&', $urls);
    }
    
    public static function setEid($id, $str = 'i') {
        if (!$str) {
            $str = self::createCode(4);
        }
        return self::setCryptNum($id, $str);
    }
    public static function getEid($id, $str = 'i') {
        if (!$str) {
            $str = 'abcd';
        }
        return self::getCryptNum($id, $str);
    }
    
    public static function reptHtml($data) {
        $content = preg_replace("/<a[^>]*>/i", "", $content);
        $content = preg_replace("/<\/a>/i", "", $content);
        $content = preg_replace("/<div[^>]*>/i", "", $content);
        $content = preg_replace("/<\/div>/i", "", $content);
        $content = preg_replace("/<!--[^>]*-->/i", "", $content); //注释内容
        $content = preg_replace("/style=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/class=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/id=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/lang=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/width=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/height=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/border=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/face=.+?['|\"]/i", '', $content); //去除样式
        $content = preg_replace("/face=.+?['|\"]/", '', $content); //去除样式只允许
        /**/
    }
    
    public static function down_file($filepath, $filename) {
        if (!file_exists($filepath)) {
            echo "backup error ,download file no exist";
            exit();
        }
        ob_end_clean();
        header('Content-Type: application/download');
        header("Content-type: text/csv");
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header("Content-Encoding: binary");
        header("Content-Length:" . filesize($filepath));
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($filepath);
        $e = ob_get_contents();
        ob_end_clean();
    }
    
    public static function isWage() {
        $id = self::getFormid();
        $data = array(
            1 => '',
            5139 => '深圳市卡森家具有限公司',
            3930 => '佛山市木之源家具有限公司',
            4960 => '深圳市金品家具有限公司',
            2446 => '深圳市泰之林家具限公司',
            4083 => '东莞市圣发家具有限公司',
            11882 => 'test',
            11906 => '梅小琴test',
            5290 => '深圳市智升家居有限公司',
        );
        return isset($data[$id]);
    }
    public static function isCost() {
        $id = self::getFormid();
        $data = array(
            1 => '',
            5139 => '深圳市卡森家具有限公司',
            3930 => '佛山市木之源家具有限公司',
            4960 => '深圳市金品家具有限公司',
            2446 => '深圳市泰之林家具限公司',
            5471 => '深圳市冠邦世佳家居有限公司',
            4973 => '深圳市欧廷美居家具有限公司',
            4083 => '东莞市圣发家具有限公司',
            
            11882 => 'test',
            11906 => '梅小琴test',
            5290 => '深圳市智升家居有限公司',
            5112 => '惠州市中港龙家具制造有限公司 ',
        );
        return isset($data[$id]);
    }
    
    public static function getMsgByErrors($errors, $one = true) {
        if ($one) {
            $result = "<ul>";
        }
        if (is_array($errors)) {
            foreach ($errors as $key => $value) {
                if (is_array($value)) {
                    $result.= self::getMsgByErrors($value, fasel);
                } else {
                    $result.= sprintf("<li>%s</li>", $value);
                }
            }
        }
        if ($one) {
            $result.= "</ul>";
        }
        return $result;
    }
    /**
     * 注册js模块
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    public static function regWebModule($module) {
        if ($module == 'dialog') {
            $path = '_ak/js/plugins/jq.artDialog/';
            $scrpitS = $path . 'dialog-plus-min.js';
            $cssS = $path . 'css/ui-dialog.css';
            self::regScriptFile($scrpitS, 'static');
            self::regCssFile($cssS, 'static');
        }
    }
}
/*
    $id = '44773236400282037';
    $id16 = dechex($id);
    $idstr = Tak::setCryptKey($id16);
    $idstr116 = Tak::setCryptKey($id16);
    
    echo sprintf("%s\t%s\n",strlen($id),$id);
    echo sprintf("%s\t%s\n",strlen($id16),$id16);
    echo sprintf("%s\t%s\n",strlen($idstr1),$idstr1);
    echo sprintf("%s\t%s\n",strlen($idstr116),$idstr116);
    
    echo sprintf("%s\n 16 to 10 \n ",hexdec('536aed2fb09bc90c1d8b456a'));
*/
