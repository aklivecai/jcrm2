<?php
class Clientele extends DbRecod {
    public $linkName = 'clientele_name'; /*连接的显示的字段名字*/
    public $profession = 4;
    public static $table = '{{clientele}}';
    /**
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                ' clientele_name, industry, profession',
                'required'
            ) ,
            array(
                'annual_revenue, employees, display, status,vipid',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid, manageid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, last_time, add_time, add_ip, modified_time, modified_ip,vipid',
                'length',
                'max' => 10
            ) ,
            array(
                'clientele_name, rating, industry, profession, origin, email',
                'length',
                'max' => 100
            ) ,
            array(
                'address, note',
                'length',
                'max' => 255
            ) ,
            array(
                'telephone, fax, web',
                'length',
                'max' => 50
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, fromid, manageid, clientele_name, rating, annual_revenue, qw, profession, origin, employees, email, address, telephone, fax, web, display, status, last_time, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'clientele_name',
                'checkRepetition'
            ) ,
            array(
                'vipid',
                'checkVipid'
            ) ,
        );
    }
    /**
     * 检验重复
     */
    public function checkVipid($attribute, $params) {
        $val = $this->$attribute;
        if ($val == 0) {
            return true;
        }
        $sql = array(
            sprintf('fromid=%s', Ak::getFormid()) ,
            sprintf(":col='%s'", $val) ,
        );
        $arr = array(
            ':col' => $attribute,
        );
        if ($this->primaryKey > 0) {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = $this->primaryKey();
            $arr[':itemid'] = $this->primaryKey;
        }
        
        $sql = implode(' AND ', $sql);
        // Tak::KD(strtr($sql,$arr),1);
        // Tak::KD($arr,1);
        // 查找满足指定条件的结果中的第一行
        $sql = strtr($sql, $arr);
        $sql = sprintf('SELECT COUNT(1) FROM %s WHERE %s', self::$table, $sql);
        $command = self::$db->createCommand($sql);
        $dataReader = $command->queryScalar();
        // Tak::KD($m,1);
        $result = true;
        $error = null;
        if ($dataReader > 0) {
            $error = sprintf('%s: %s 已经存在 :', $this->getAttributeLabel($attribute) , $val);
        } else {
            $sql = sprintf('SELECT COUNT(1) FROM %s WHERE itemid=%s', TestMemeber::$table, $val);
            $dataReader = self::$db->createCommand($sql)->queryScalar();
            if ($dataReader == 0) {
                $error = sprintf('%s: %s 不存在 ', $this->getAttributeLabel($attribute) , $val);
            }
        }
        if ($error) {
            $this->addError($attribute, $error);
            $result = false;
        }
        return $result;
    }
    public function checkRepetition($attribute, $params) {
        $sql = array(
            'manageid>0', //防止重复查找公海内的客户
            sprintf('fromid=%s', Ak::getFormid()) ,
            sprintf("LOWER(:col)='%s'", strtolower(addslashes($this->$attribute))) ,
        );
        $arr = array(
            ':col' => $attribute,
        );
        if ($this->primaryKey > 0) {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = $this->primaryKey();
            $arr[':itemid'] = $this->primaryKey;
        }
        
        $sql = implode(' AND ', $sql);
        // Tak::KD(strtr($sql,$arr),1);
        // Tak::KD($arr,1);
        // 查找满足指定条件的结果中的第一行
        $sql = strtr($sql, $arr);
        $sql = sprintf('SELECT status,manageid,itemid FROM %s WHERE %s', self::$table, $sql);
        // Tak::KD($sql);
        $command = self::$db->createCommand($sql);
        $row = $command->queryRow();
        // Tak::KD($row, 1);
        $result = true;
        if ($row) {
            $user_nicename = Manage::getNameById($row['manageid']);
            $err = sprintf('%s: [%s] 已经存在 ,属于[%s]所有', $this->getAttributeLabel($attribute) , $this->$attribute, $user_nicename);
            if ($row['status'] == '3') {
                $err.= sprintf(',信息在公海里面,%s;', JHtml::link('点击查看', array(
                    'clientele/showSeas',
                    'id' => $row['itemid'],
                )));
            } elseif ($row['status'] == '0') {
                $err.= ',信息在其回收站中;';
            }
            // Tak::KD($err, 1);
            // $err.= $m->getHtmlLink();
            $this->addError($attribute, $err);
            $result = false;
        }
        return $result;
    }
    /**
     * @return array relational rules. 表的关系，外键信息
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'iManage' => array(
                self::BELONGS_TO,
                'Manage',
                'manageid',
                'select' => 'user_nicename'
            ) ,
        );
    }
    /**
     * @return array customized attribute labels (name=>label) 字段显示的
     */
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'manageid' => '所有者',
            'clientele_name' => '客户名称',
            'rating' => '客户等级',
            'annual_revenue' => '年营业额',
            'industry' => '客户类型', /*(新客户,意向客户,潜在客户,正式客户,VIP客户)*/
            'profession' => '客户行业',
            'origin' => '来源', /*(电话营销,主动来电,老客户,朋友介绍,广告杂志,互联网,其它)*/
            'employees' => '员工数量',
            'email' => '邮箱',
            'address' => '地址',
            'telephone' => '电话',
            'fax' => '传真',
            'web' => '网站',
            'display' => '显示情况', /*(0:自己,1：公共)*/
            'status' => '状态', /*(0:回收站,1:正常)*/
            'last_time' => '最后联系时间', /*(客户联系记录中修改)*/
            'add_time' => '添加时间',
            'add_us' => '录入者',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'vipid' => '企业编号',
            'note' => '备注',
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $condition = array();
        if (isset($arr['condition'])) {
            $condition[] = $arr['condition'];
        }
        $arr['order'] = $this->getConAlias('last_time DESC ');
        $arr['condition'] = implode(" AND ", $condition);
        
        return $arr;
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('fromid', $this->fromid);
        if ($this->manageid) {
            $criteria->compare('manageid', $this->manageid);
        }
        
        $criteria->compare('clientele_name', $this->clientele_name, true);
        $criteria->compare('rating', $this->rating);
        $criteria->compare('annual_revenue', $this->annual_revenue);
        $criteria->compare('industry', $this->industry);
        $criteria->compare('profession', $this->profession);
        $criteria->compare('origin', $this->origin);
        $criteria->compare('employees', $this->employees);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('telephone', $this->telephone, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('web', $this->web, true);
        
        $criteria->compare('display', $this->display);
        $criteria->compare('status', $this->status);
        
        $this->setCriteriaTime($criteria, array(
            'last_time',
            'add_time',
            'modified_time'
        ));
        $criteria->compare('note', $this->note, true);
        
        $permission = Ak::getQuery('permission', false);
        if ($permission && $permission > 0) {
            $permission = $permission;
            $sql = sprintf("manageid in (SELECT manageid FROM %s WHERE fromid=%s AND branch=$permission)", Manage::$table, Ak::getFormid());
            $criteria->addCondition($sql);
        }
        
        return $cActive;
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    private $_prsons = null;
    public function getProsons() {
        if ($this->_prsons === null) {
            $m = ContactpPrson::model()->setGetCU();
            $m->scondition = false;
            $this->_prsons = $m->findAllByAttributes(array(
                'clienteleid' => $this->itemid
            ));
        }
        return $this->_prsons;
    }
    //转移联系记录和联系人用语句修改客户归属，防止重名查找，导致不成功
    public function move() {
        $this->setCCMid($this->manageid, $this->primaryKey, true);
        return true;
    }
    
    protected function _del() {
        AdminLog::$isLog = false;
        $tags = $this->getProsons();
        foreach ($tags as $key => $value) {
            $value->del();
        }
        AdminLog::$isLog = true;
        return true;
    }
    public function del() {
        $result = parent::del();
        if ($result) {
            $this->setCCMid();
            /* $this->_del();*/
        }
        return $result;
    }
    
    public function setRestore() {
        $result = parent::setRestore();
        if ($result) {
            $this->setCCMid($this->manageid);
            /*
            $tags = $this->getProsons();
            foreach ($tags as $key => $value) {
                $value->isLog = false;
                $value->setRestore();
            }
            */
        }
        return $result;
    }
    //删除客户或者,还原客户,丢公海,和捞起来
    private function setCCMid($mid = 0, $itemid = 0, $clientele = false) {
        $itemid == 0 && $itemid = $this->primaryKey;
        $mid == - 1 && $mid = Ak::getManageid();
        $sqls = array(
            'conatact' => 'UPDATE :contact SET manageid=:mid WHERE fromid=:fromid AND  clienteleid=:itemid',
            'contactpprson' => 'UPDATE :contactpprson SET manageid=:mid WHERE fromid=:fromid AND  clienteleid=:itemid',
        );
        $data = array(
            ':table' => Clientele::$table,
            ':contact' => Contact::$table,
            ':contactpprson' => ContactpPrson::$table,
            ':fromid' => $this->fromid,
            ':mid' => $mid,
            ':itemid' => $itemid,
        );
        if ($clientele) {
            $sqls[] = 'UPDATE :table SET manageid=:mid WHERE fromid=:fromid AND  itemid=:itemid';
        }
        
        $command = self::$db->createCommand('');
        foreach ($sqls as $key => $value) {
            $sql = strtr($value, $data);
            $command->text = $sql;
            $totals = $command->execute();
        }
    }
    // 进公海
    public function setSeas() {
        $result = false;
        if ($this->status != 3) {
            $this->isLog = false;
            $this->status = 3;
            if ($this->save()) {
                $this->setCCMid();
                $result = true;
                AdminLog::log($this->sName . '-' . Tk::g('仍进公海') . ' - 编号:' . $this->primaryKey);
            } else {
                $arr = $this->getErrors();
            }
        }
        return $result;
    }
    /**
     * 返回捞起来的结果
     * @param  boolean $manageid [description]
     * @return [type]            [description]
     */
    public function getBySeas($manageid = false) {
        $result = true;
        if ($manageid == false) {
            $manageid = Tak::getManageid();
        }
        $this->isLog = false;
        $this->status = 1;
        if ($this->manageid != $manageid) {
            //不是同一个人，用语句修改客户归属，防止重名查找，导致不成功
            $this->setCCMid($manageid, $this->primaryKey, true);
        } elseif ($this->save()) {
            //同一个人，自己丢自己捡，只需要修改联系人和联系记录即可
            $this->setCCMid($this->manageid);
            $result = true;
            AdminLog::log($this->sName . '-' . Tk::g('在公海捞起') . ' - 编号:' . $this->primaryKey);
        } else {
            $result = false;
            Tak::KD($this->getErrors() , 1);
        }
        return $result;
    }
    
    protected function afterDelete() {
        parent::afterDelete();
        $tags = $this->getProsons();
        foreach ($tags as $key => $value) {
            $value->isLog = false;
            $value->delete();
        }
    }
    
    protected function getDefaultScopeSql() {
        $sqlWhere = $this->defaultScope(0);
        if (is_array($sqlWhere) && $sqlWhere['condition']) {
            $sqlWhere = is_array($sqlWhere['condition']) ? implode(' AND ', $sqlWhere['condition']) : $sqlWhere['condition'];
        } else {
            $sqlWhere = '';
        }
        if ($this->manageid > 0) {
            if ($sqlWhere) {
                $sqlWhere.= ' AND ';
            }
            $sqlWhere.= sprintf(' manageid=%s', $this->manageid);
        }
        return $sqlWhere;
    }
}
