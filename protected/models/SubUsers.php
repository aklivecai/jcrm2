<?php
class SubUsers extends CActiveRecord {
    public $isbranch = false;
    public $branch = 0;
    public $manageid = 0;
    public $fromid = 0;
    public function primaryKey() {
        return 'manageid';
    }
    public function tableName() {
        return Manage::$table;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        return $arr;
    }
    
    public function rules() {
        return array(
            array(
                'manageid',
                'required'
            ) ,
            array(
                'manageid',
                'checkMid'
            ) ,
            array(
                'fromid,branch,isbranch',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'manageid, isbranch, branch, fromid',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        $result = array(
            'manageid' => $this->branch ? '管理员' : '员工',
        );
        return $result;
    }
    
    public function getSql($not = false) {
        $fromid = $this->fromid;
        $manageid = $this->manageid;
        $contion = array(
            ' isbranch=' . ($this->isbranch ? 0 : 1) ,
            sprintf("manageid<>'%s'", $this->manageid) ,
        );
        if ($not) {
            $contion[] = ' branch <>' . $this->branch;
        } else {
            $contion[] = ' branch =' . $this->branch;
        }
        $contion = sprintf(' (%s) ', implode(' AND  ', $contion));
        $sql = array(
            $contion
        );
        
        $tabl = Subordinate::$table;
        /*经理*/
        if ($this->isbranch) {
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
        
        $sql[] = $temp;
        if ($not) {
            $sql = implode(' AND ', $sql);
        } else {
            $sql = implode(' OR ', $sql);
        }
        
        return $sql;
    }
    public function checkMid($attribute, $params) {
        $sql = array();
        $sql = implode(' AND ', $sql);
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        if ($m != null) {
            $err = $this->getAttributeLabel($attribute) . ' 已经存在 :';
            $err.= $m->getHtmlLink();
            $this->addError($attribute, $err);
        }
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
        $sql = strtr('SELECT manageid,user_nicename,user_name AS title,fromid FROM :tabl  WHERE  :sql', array(
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
        $tags = self::$db->createCommand($sql)->queryAll();
        $result = array();
        $branchs = Permission::getList();
        foreach ($tags as $key => $value) {
            $value['active'] = $value['branch'] == $this->branch;
            if (isset($branchs[$value['branch']])) {
                $value['branch_name'] = $branchs[$value['branch']];
            }
            $result[$value['manageid']] = $value;
        }
        return $result;
    }
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->addCondition($sql);
        $pageSize = parent::getPageSize();
        $pageSize = 999;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ) ,
        ));
    }
}
