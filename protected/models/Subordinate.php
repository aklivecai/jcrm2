<?php
/*
    部门属下信息调整，查询
    只查询属下们的客户情况
*/

class Subordinate extends DbiRecod {
    public static $table = '{{subordinate}}';
    
    public static $mos = null;
    
    public $isbranch = false;
    public $branch = 0;
    public $manageid = 0;
    public $fromid = 0;
    
    public static function iniMods() {
        if (self::$mos == null) {
            # code...
            self::$mos = array(
                'fromid' => Tak::getFormid() ,
                'manageid' => Tak::getManageid() ,
                'branch' => Tak::getState('branch', -1) ,
                'isbranch' => Tak::getState('isbranch', false)
            );
        }
        return self::$mos;
    }
    
    public function initMos($mos = false) {
        if ($mos) {
            self::$mos = $mos;
        } elseif (self::$mos == null) {
            self::iniMods();
        }
        foreach (self::$mos as $key => $value) {
            if (isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
        return self::$mos;
    }
    
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    
    public static function getSqlSub($not = false) {
        $mods = self::iniMods();
        $tabl = self::$table;
        $fromid = $mods['fromid'];
        $manageid = $mods['manageid'];
        $isbranch = $mods['isbranch'];
        /*经理*/
        if ($isbranch) {
            $temp = "SELECT mid FROM $tabl WHERE fromid=$fromid AND  manageid=$manageid";
        } else {
            /*员工*/
            $temp = "SELECT manageid FROM $tabl WHERE fromid=$fromid AND mid=$manageid";
        }
        if ($not) {
            $temp = sprintf("(manageid NOT IN (%s))", $temp);
        } else {
            $temp = sprintf("(manageid IN (%s))", $temp);
        }
        return $temp;
    }
    
    public function getSql($not = false) {
        $this->initMos();
        $arr = array(
            ':fromid' => $this->fromid,
            ':manageid' => $this->manageid,
            ':branch' => $this->branch,
        );
        $isbranch = $this->isbranch;
        $contion = array(
            "fromid=:fromid",
            'isbranch=' . ($isbranch ? 0 : 1) ,
            "manageid<>:manageid",
        );
        if ($not) {
            $contion[] = ' branch <>:branch';
        } else {
            $contion[] = ' branch =:branch';
        }
        $contion = sprintf(' (%s) ', implode(' AND ', $contion));
        $sql = array(
            $contion
        );
        
        $sql[] = $this->getSqlSub($not);
        
        if ($not) {
            $sql = implode(' AND ', $sql);
        } else {
            $sql = implode(' OR ', $sql);
        }
        // Tak::KD(self::initMos());
        // Tak::KD($sql);
        $sql = strtr($sql, $arr);
        // Tak::KD($sql);
        return $sql;
    }
    
    public function getNotUser($q = false) {
        $contion = array(
            'fromid=' . $this->fromid,
            $this->getSql(true) ,
        );
        $where = implode(' AND ', $contion);
        if ($q) {
            $where.= ' OR user_nicename LIKE :name OR  user_name LIKE :name ';
        }
        $sql = strtr('SELECT manageid AS itemid,user_nicename,user_name AS title,fromid FROM :tabl  WHERE  :sql', array(
            ':tabl' => Manage::$table,
            ':sql' => $where
        ));
        // Tak::KD($sql);
        $tags = self::$db->createCommand($sql)->queryAll(true, array(
            ':name' => $q
        ));
        $result = array();
        foreach ($tags as $key => $value) {
            $result[] = $value;
        }
        return $result;
    }
    public function getData() {
        $contion = array(
            $this->getSql() ,
        );
        $where = implode(' ADN ', $contion);
        $sql = strtr('SELECT manageid,user_nicename,branch FROM :tabl WHERE  :sql', array(
            ':tabl' => Manage::$table,
            ':sql' => $where
        ));
        // Tak::KD($sql);
        $tags = self::$db->createCommand($sql)->queryAll();
        $result = array();
        $branchs = Permission::getList();
        foreach ($tags as $key => $value) {
            $value['active'] = $value['branch'] == $this->branch;
            if (isset($branchs[$value['branch']])) {
                $value['branch_name'] = $branchs[$value['branch']];
            } else {
                $value['branch_name'] = '用户';
            }
            $result[$value['manageid']] = $value;
        }
        return $result;
    }
    
    public static function getSubManageSql() {
        $mod = self::iniMods();
        $sql = strtr('manageid IN (SELECT mid FROM :tabl WHERE fromid=:fromid AND  manageid=:manageid) OR  manageid IN (SELECT manageid FROM :tabl_manage WHERE fromid=:fromid AND branch=:branch AND isbranch>=0)', array(
            ':tabl' => self::$table,
            ':tabl_manage' => Manage::$table,
            ':fromid' => $mod['fromid'],
            ':branch' => $mod['branch'],
            ':manageid' => $mod['manageid']
        ));
        return $sql;
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $condition = array();
        $condition[] = 'fromid=' . Tak::getFormid();
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    public function rules() {
        return array(
            array(
                'manageid',
                'required'
            ) ,
            array(
                'manageid,mid,fromid,branch,isbranch',
                'numerical',
                'integerOnly' => true
            ) ,
        );
    }
    public function attributeLabels() {
        $mos = self::initMos();
        return array(
            'fromid' => '平台会员ID',
            'manageid' => $mos['isbranch'] == 0 ? '管理员' : '员工',
            'mid' => '下属',
        );
    }
    
    public function delto($id) {
        $mos = self::initMos();
        if ($mos['isbranch'] == 1) {
            $this->mid = $id;
        } else {
            $t = $this->manageid;
            $this->manageid = $id;
            $this->mid = $t;
        }
        $arr = array(
            ':fromid' => $this->fromid,
            ':manageid' => $this->manageid,
            ':mid' => $this->mid,
        );
        $table = self::$table;
        $sql = "DELETE FROM  $table WHERE manageid=:manageid AND mid=:mid AND fromid=:fromid";
        self::$db->createCommand($sql)->execute($arr);
        return $arr;
    }
    public function saveto($id, $erro) {
        if ($erro == null) {
            $this->addError('manageid', sprintf('请选择 %s', $this->getAttributeLabel('manageid')));
            return false;
        }
        $arr = $this->delto($id);
        $table = self::$table;
        $sql = "INSERT INTO $table (manageid,mid,fromid) VALUES(:manageid,:mid,:fromid)";
        self::$db->createCommand($sql)->execute($arr);
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave(true);
        if ($result) {
            $this->fromid = Ak::getFormid();
        }
    }
    public static function getUsers() {
        $m = new self;
        $result = $m->getData();
        foreach ($result as $key => $value) {
            $result[$key] = $value['user_nicename'];
        }
        return $result;
    }
    public static function getDb() {
        if (self::$db == null) {
            self::$db = Ak::db(true);
        }
        return self::$db;
    }
}
