<?php
class Manage extends DbRecod {
    public $linkName = 'user_name'; /*连接的显示的字段名字*/
    public $branch = 0;
    public $isbranch = 0;
    
    private $oldbranch = false;
    
    public function primaryKey() {
        return 'manageid';
    }
    
    public static $table = '{{manage}}';
    /*记录修改过部门*/
    public function changeBranch() {
        $this->oldbranch = $this->branch;
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'user_name, user_pass,branch,user_nicename',
                'required'
            ) ,
            array(
                'login_count,branch,isbranch',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'user_name',
                'length',
                'max' => 60
            ) ,
            array(
                'user_pass',
                'length',
                'min' => 5
            ) ,
            array(
                'user_pass, user_nicename, activkey',
                'length',
                'max' => 64
            ) ,
            array(
                'salt, add_time, last_login_time',
                'length',
                'max' => 10
            ) ,
            array(
                'user_email',
                'length',
                'max' => 100
            ) ,
            array(
                'user_status',
                'length',
                'max' => 11
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'add_ip, last_login_ip',
                'safe'
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'user_name, user_pass, salt, user_nicename, user_email, add_time, last_login_time, login_count, user_status, note,active_time,fromid',
                'safe',
                'on' => 'search'
            ) ,
            
            array(
                'user_name',
                'checkRepetition'
            ) ,
        );
    }
    /**
     * 检验重复
     */
    public function checkRepetition($attribute, $params) {
        $sql = array(
            "LOWER(:col)=:val"
        );
        $arr = array(
            ':col' => $attribute,
        );
        if ($this->primaryKey > 0) {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = $this->primaryKey();
            $arr[':itemid'] = $this->primaryKey;
            $sql[] = 'fromid=' . $this->fromid;
        } else {
            $sql[] = 'fromid=' . Ak::getFormid();
        }
        
        $sql = implode(' AND ', $sql);
        // Tak::KD(strtr($sql,$arr),1);
        // if (Tak::getAdmin())      Tak::KD(strtr($sql,$arr),1);
        // 查找满足指定条件的结果中的第一行
        
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        // Tak::KD($m,1);
        
        if ($m != null) {
            $err = $this->getAttributeLabel($attribute) . ' 已经存在 :';
            $err.= $m->getHtmlLink();
            $this->addError($attribute, $err);
        }
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = array(
            'order' => 'add_time DESC'
        );
        $condition = array();
        if ($this->hasAttribute('fromid')) {
            $fromid = Ak::getFormid();
            if ($fromid > 0) {
                $condition[] = 'fromid=' . $fromid;
                /*$condition[] = "user_name<>'admin'";*/
            }
        }
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // Tak::KD($this->getAttributes());
        return array(
            'iBranch' => array(
                self::HAS_ONE,
                'Permission',
                'branch',
                'condition' => '',
                'select' => 'name,description',
                'order' => ''
                /*,'on'=>'name='.$this->branch*/
            ) ,
        );
    }
    public function search() {
        $criteria = new CDbCriteria;
        if (Tak::getAdmin()) {
            if ($this->fromid) {
                $criteria->compare('fromid', $this->fromid);
            } else {
                $criteria->addCondition("fromid=" . Ak::getFormid());
            }
        } else {
            $criteria->addCondition("fromid=" . Ak::getFormid());
            $criteria->addCondition("user_name<>'admin'");
        }
        
        $criteria->compare('manageid', $this->manageid);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('user_nicename', $this->user_nicename, true);
        $criteria->compare('user_email', $this->user_email, true);
        $criteria->compare('login_count', $this->login_count);
        $criteria->compare('note', $this->note, true);
        if ($this->branch >= 0) {
            $criteria->compare('branch', $this->branch);
        }
        if ($this->user_status >= 0) {
            $criteria->compare('user_status', $this->user_status);
        }
        $this->setCriteriaTime($criteria, array(
            'add_time',
            'last_login_time'
        ));
        $pageSize = parent::getPageSize();
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ) ,
        ));
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    function del() {
        $result = false;
        if ($this->user_status != 0) {
            $this->user_status = 0;
            $this->save();
            $result = true;
        }
        return result;
    }
    
    public function attributeLabels() {
        return array(
            'fromid' => '平台会员ID',
            'manageid' => '管理员编号',
            'user_name' => '登录帐号',
            'user_pass' => '登录密码',
            'salt' => '登录检验码',
            'user_nicename' => '名字',
            'user_email' => '邮箱',
            'isbranch' => '职位',
            'branch' => '部门',
            'add_time' => '添加时间',
            'add_ip' => '添加IP',
            'last_login_time' => '最后登录',
            'last_login_ip' => '最后登录IP',
            'login_count' => '登录次数',
            'user_status' => '状态',
            'note' => '备注',
            'activkey' => '激活Key',
            'active_time' => '最后活动',
        );
    }
    //保存数据前
    protected function beforeSave() {
        $result = true || parent::beforeSave(true);
        if ($result) {
            //添加数据时候
            if ($this->isNewRecord) {
                $arr = Tak::getOM();
                $this->manageid = $arr['itemid'];
                $this->add_time = $arr['time'];
                $this->add_ip = $arr['ip'];
                $this->fromid = $arr['fromid'];
                $this->salt = $this->generateSalt();
                if (!$this->user_status) {
                    $this->user_status = TakType::STATUS_DELETED;
                }
                $this->user_pass = $this->hashPassword($this->user_pass, $this->salt);
            } else {
                //修改数据时候
                if (!Tak::isValidMd5($this->user_pass)) {
                    $this->user_pass = $this->hashPassword($this->user_pass, $this->salt);
                }
                if (!isset($this->user_status)) {
                    $this->user_status = TakType::STATUS_DELETED;
                }
            }
        }
        return $result;
    }
    //
    protected function afterSave() {
        parent::afterSave();
        // return $result;
        //新建用户,插入部门权限
        //存在替换
        $sql = false;
        $arr = array(
            ':itemname' => $this->branch,
            ':fromid' => $this->fromid,
            ':userid' => $this->manageid,
            ':tabl' => '{{rbac_authassignment}}',
        );
        if ($this->isNewRecord || $this->oldbranch >= 0) {
            $comm = Tak::getDb('db')->createCommand();
            // Tak::KD($this->oldbranch);
            // 删除就记录
            if ($this->oldbranch >= 0 && $this->oldbranch != '') {
                $arr[':oldbranch'] = $this->oldbranch;
                //$sql = "DELETE FROM :tabl WHERE fromid=:fromid AND  userid=:userid AND itemname=':oldbranch'";
                $sql = "DELETE FROM :tabl WHERE fromid=:fromid AND  userid=:userid";
                $sql = strtr($sql, $arr);
                $comm->setText($sql)->execute();
                // $db->createCommand($sql)->execute();
                // Tak::KD($sql,1);
                
                
            }
            // 判断是否已经存在 queryScalar
            $sql = " SELECT COUNT(*) FROM :tabl WHERE fromid=:fromid AND  userid=:userid AND itemname=':itemname'";
            $sql = strtr($sql, $arr);
            $rows = $comm->setText($sql)->queryScalar();
            // Tak::KD($rows);
            // Tak::KD($rows,1);
            if ($rows == 0) {
                $sql = "INSERT INTO :tabl (itemname,fromid,userid,data) VALUES(:itemname,:fromid,:userid,'N;')";
                $sql = strtr($sql, $arr);
                // $db->createCommand($sql)->execute();
                $comm->setText($sql)->execute();
                // Tak::KD($sql,1);
                
                
            }
            // exit;
            
            
        }
    }
    
    public static function getNameById($manageid) {
        $sql = " SELECT user_nicename FROM :tableName 
        WHERE
             fromid = :fromid
             AND manageid = :manageid
        ";
        $sql = strtr($sql, array(
            ':tableName' => self::$table,
            ':fromid' => AK::getFormid() ,
            ':manageid' => $manageid,
        ));
        $query = Ak::db(true)->createCommand($sql)->queryScalar();
        return $query;
    }
    /**
     * *更新最后活跃时间
     * @return [type] [description]
     */
    public function upActivkey() {
        $arr = Tak::getOM();
        $sql = " UPDATE :tableName SET
            active_time = :active_time
        WHERE
             fromid = :fromid
             AND manageid = :manageid
        ";
        $sql = strtr($sql, array(
            ':tableName' => $this->tableName() ,
            ':active_time' => $arr['time'],
            ':fromid' => $arr['fromid'],
            ':manageid' => $arr['manageid']
        ));
        $query = Ak::db(true)->createCommand($sql);
        $query->execute();
        AdminLog::log('退出操作');
        return true;
    }
    // 更新登录次数，时间信息
    public function upLogin() {
        $arr = Tak::getOM();
        $sql = " UPDATE :tableName SET
            last_login_ip = :last_login_ip
            ,login_count = login_count+1
            ,last_login_time = :last_login_time
        WHERE
             fromid = :fromid
             AND manageid = :manageid
        ";
        $sql = strtr($sql, array(
            ':tableName' => $this->tableName() ,
            ':last_login_ip' => $arr['ip'],
            ':last_login_time' => $arr['time'],
            ':fromid' => $arr['fromid'],
            ':manageid' => $arr['manageid']
        ));
        $query = Ak::db(true)->createCommand($sql);
        $query->execute();
        AdminLog::log('登录操作');
        return true;
    }
    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password) {
        $chPass = $password;
        if (!Tak::isValidMd5($password)) {
            $chPass = $this->hashPassword($password, $this->salt);
        }
        return $chPass === $this->user_pass;
    }
    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password, $salt) {
        return md5($salt . $password);
    }
    /**
     * 生成一个激活Key
     * @return string
     */
    public function generateActivkey() {
        return md5(uniqid($this->user_name . $this->user_pass, true));
    }
    /**
     * 生成一个SALT码
     */
    public function generateSalt() {
        $seed = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; //   输出字符集
        for ($i = 0;$i < 5;$i++) $seed = str_shuffle($seed);
        $salt = substr($seed, 0, 4);
        return $salt;
    }
    
    public  function getList() {
        $sql = 'SELECT manageid,user_name,user_nicename,branch FROM :table WHERE fromid=:fromid AND user_status=1 ORDER BY branch ASC';
        $data = array(
            ':table' => self::$table,
            ':fromid' => Tak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        $tags = self::$db->createCommand($sql)->queryAll();
        return $tags;
    }
}
