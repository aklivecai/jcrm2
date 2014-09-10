<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class InitForm extends CFormModel {
    public $username;
    public $password;
    public $fromid;
    
    public function __construct() {
        parent::__construct();
    }
    public function rules() {
        return array(
            // username and password are required
            array(
                'username, password,fromid',
                'required'
            ) ,
            // rememberMe needs to be a boolean
            // password needs to be authenticated
            array(
                'fromid',
                'fromiDecode'
            ) ,
            array(
                'password',
                'authenticate'
            ) ,
        );
    }
    
    public function fromiDecode($attribute, $params) {
        if (!$this->fromid) {
            $this->addError('fromid', '非法操作！！');
        }
    }
    
    public function authenticate($attribute, $params) {
        $m = TestMemeber::model()->getMmeber($this->fromid);
        
        if ($m['user_name'] != '') {
            $ch = $m['user_name'];
        } else {
            $ch = 'admin';
        }
        
        if ($this->username != $this->password || $this->username != $ch) {
            $str = '激活帐号或者密码错误！';
            $this->addError('password', $str);
        }
    }
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '激活帐号',
            'password' => '密码',
            'fromid' => '商铺编号',
        );
    }
    
    public function install($admin = 'admin', $password = false) {
        $connection = Tak::getDb('db');
        $transaction = $connection->getCurrentTransaction();
        if (!$password) {
            $password = 'c0b266594634df51f07796e7ca31107e';
            $salt = 'gXmz';
        }
        if (!Tak::isValidMd5($password)) {
            $mod = new Manage;
            $salt = $mod->generateSalt();
            $password = $mod->hashPassword($password, $salt);
        }
        if ($transaction !== null) {
            // Transaction already started outside
            $transaction = null;
        } else {
            // There is no outer transaction, creating a local one
            $transaction = $connection->beginTransaction();
        }
        $sqls = array();
        try {
            $time = Tak::now();
            $itemid = Tak::fastUuid();
            $ip = Tak::IP2Num(Tak::getip());
            $arr = array(
                ':time' => $time,
                ':ip' => $ip,
                ':itemid' => $itemid,
                ':uname' => $this->username,
                ':fromid' => $this->fromid,
                ':tab_Manage' => Manage::$table,
                ':tab_rabc' => '{{rbac_authassignment}}',
                ':tab_AddressG' => AddressGroups::$table,
                ':tab_AddressB' => AddressBook::$table,
                ':tab_type' => Category::$table,
                ':tab_warehouse' => Warehouse::$table,
                ':tab_admin_log' => AdminLog::$table,
                ':tab_test_memeber' => TestMemeber::$table,
                ':salt' => $salt,
                ':password' => $password,
            );
            
            if (is_string($admin)) {
                $admin = array(
                    $admin
                );
                if ($admin[0] != 'admin') {
                    $admin[] = 'admin';
                }
            }
            
            foreach ($admin as $key => $value) {
                if ($value != 'admin') {
                    $arr[":userid$key"] = $this->fromid;
                } else {
                    $arr[":userid$key"] = Tak::fastUuid();
                }
                $arr[":admin$key"] = $value;
                //插入管理帐号
                $sqls[] = "INSERT INTO :tab_Manage VALUES(:fromid,:userid$key,0,0,':admin$key',':password',':salt','管理员:admin$key','',:time,0,0,0,0,1,'','',0);";
                //插入权限
                $sqls[] = "INSERT INTO :tab_rabc (`itemname`,`fromid`,`userid`,`bizrule`,`data`) VALUES ('Admin',:fromid, :userid$key, '', 'N;');";
                // Tak::KD($sqls);
            }
            $arr[':userid'] = $this->fromid;
            // Tak::KD($admin);
            // Tak::KD($arr);
            
            // #通讯录组
            $sqls[] = "INSERT INTO :tab_AddressG (`address_groups_id`, `fromid`, `name`, `display`, `add_time`, `add_us`, `add_ip`, `modified_time`, `modified_us`, `modified_ip`, `note`, `listorder`, `status`) VALUES (:itemid, :fromid, '销售部', 0, :time, :userid, 0, 0, 0, 0, '', 0, 1);";
            //#通讯录
            $sqls[] = " INSERT INTO :tab_AddressB  (`itemid`, `groups_id`, `fromid`, `name`, `email`, `phone`, `address`, `department`, `position`, `sex`, `longitude`, `latitude`, `location`, `display`, `add_time`, `add_us`, `add_ip`, `modified_time`, `modified_us`, `modified_ip`, `note`, `status`) VALUES (:itemid, :itemid, :fromid, '测试-张三', '', '', '', '', '业务经理', 1, '', '', '', 1, :time, :userid, 0, 0, 0, 0, '', 1);";
            //产品分类
            $sqls[] = " INSERT INTO :tab_type (`catid`, `fromid`, `module`, `item`, `catename`, `level`, `parentid`, `arrparentid`, `child`, `arrchildid`, `listorder`) VALUES (:time, :fromid, 'product', '0', '默认分类', '0', '0', '0', '0', NULL, '0');";

            // 仓库
            $sqls[] ="INSERT INTO `:tab_warehouse` (`itemid`, `fromid`, `name`, `user_name`, `telephone`, `listorder`, `note`) VALUES (:time, :fromid, '默认仓库', '', '', '0', '');";
            
            $sqls[] = " INSERT INTO :tab_admin_log (`itemid`, `fromid`, `manageid`, `user_name`, `qstring`, `info`, `ip`, `add_time`) VALUES (:itemid, :fromid, :userid, ':uname', '', '激活初始化数据', :ip, :time);";
            //开始激活用户时间
            $sqls[] = " UPDATE :tab_test_memeber SET   `start_time`=':time' WHERE `itemid` = :fromid;";
            // 如果客户没有设置过期时间即为当前时间
            $sqls[] = " UPDATE :tab_test_memeber SET  `active_time` =  ':time' WHERE `itemid` = :fromid AND  `active_time`=0;";
            
            foreach ($sqls as $value) {
                $sql = strtr($value, $arr);
                // Tak::KD($sql);
                $connection->createCommand($sql)->execute();
            }
            // exit;
        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $transaction->rollBack();
            // Tak::KD($e,1);
            // Tak::KD('ERROR!!!!!');
            return false;
        }
        return true;
    }
}
