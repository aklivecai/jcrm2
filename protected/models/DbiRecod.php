<?php
class DbiRecod extends CActiveRecord {
    public static $_db = null;
    public static $table = '';
    
    public $mName = ''; /*当前类名字*/
    public $sName = ""; /*显示名字*/
    //是否记录日志
    public $_isLog = false;
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    public function init() {
        $this->mName = get_class($this);
        $this->sName = Tk::g($this->mName);
    }
    /**
     * 获取一个加密后的主见编号
     * @return [type] [description]
     */
    private $__sid = null;
    public function getSId($id = false) {
        if ($this->__sid == null) {
            $id === false && $id = $this->primaryKey;
            $this->__sid = Ak::setSId($id);
        }
        return $this->__sid;
    }
    /**
     * 检测企业是否存在数据库配置
     * @return [type] $db
     */
    public function getDbConnection() {
        if (self::$_db !== null) return self::$_db;
        else {
            if ($db = Ak::db()) {
                self::$_db = $db;
                self::$_db->setActive(true);
            } else {
                self::$_db = self::$db;
            }
        }
        return self::$_db;
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    protected function afterSave() {
        parent::afterSave();
        if (!$this->_isLog) {
            return true;
        }
        $url = Yii::app()->request->getUrl();
        if (strpos($url, 'delete') > 0) {
            $this->logDel();
        } elseif (strpos($url, 'del') > 0) {
            AdminLog::log(Tk::g('Deletes') . $this->sName);
        } elseif (strpos($url, 'restore') > 0) {
            AdminLog::log(Tk::g('Restore') . $this->sName);
        } elseif ($this->isNewRecord) {
            AdminLog::log(Tk::g('Create') . $this->sName . ' - 编号:' . $this->primaryKey);
        } else {
            AdminLog::log(Tk::g('Update') . $this->sName);
        }
    }
    //继承类中附加条件判断
    protected function getRepetition($attribute) {
        return array();
    }
    /**
     * 检验重复
     */
    public function checkRepetition($attribute, $params) {
        $sql = $this->getRepetition($attribute);
        $sql[] = "LOWER(:col)=:val";
        $arr = array(
            ':col' => $attribute,
        );
        if ($this->primaryKey > 0) {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = $this->primaryKey();
            $arr[':itemid'] = $this->primaryKey;
        }
        $sql = implode(' AND ', $sql);
        // 查找满足指定条件的结果中的第一行
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        // Tak::KD($m,1);
        $result = true;
        if ($m != null) {
            $err = sprintf('%s <i class="label label-warning">%s</i>   已经存在', $this->getAttributeLabel($attribute) , $this->$attribute);
            $this->addError($attribute, $err);
            $result = false;
        }
        return $result;
    }
    /**
     * 检查会员编号是否存在
     * @return [type] [description]
     */
    public function checkMid($attribute, $params) {
        if ($this->$attribute == 0) {
            return true;
        }
        $sqls = $this->getRepetition($attribute);
        $sqls[] = " fromid=:fromid ";
        $sqls[] = "manageid=:val";
        $arr = array(
            ':table' => Manage::$table,
            ':val' => $this->$attribute,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = sprintf(' SELECT COUNT(1) FROM :table WHERE %s', implode(' AND ', $sqls));
        // 查找满足指定条件的结果中的第一行
        $sql = strtr($sql, $arr);
        // Tak::KD($sql, 1);
        $count = self::$db->createCommand($sql)->queryScalar();
        $result = $count > 0;
        if (!$result) {
            $err = sprintf('%s不存在', $this->getAttributeLabel($attribute));
            $this->addError($attribute, $err);
        }
        return $result;
    }
    public function getPageSize() {
        if (isset($_GET['setPageSize'])) {
            $setPageSize = (int)$_GET['setPageSize'];
            if ($setPageSize >= 0 && $setPageSize != Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize'])) {
                Yii::app()->user->setState('pageSize', $setPageSize);
            }
            unset($_GET['setPageSize']);
            $pageSize = $setPageSize;
        } else {
            $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        }
        return $pageSize;
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        $pageSize = $this->getPageSize();
        $colV = Yii::app()->request->getQuery('dt', false);
        if ($colV && $colV != '' && isset($_GET['col']) && $this->hasAttribute($_GET['col'])) {
            $date = Tak::searchData($colV);
            if ($date) {
                $criteria->addBetweenCondition($_GET['col'], $date['start'], $date['end']);
            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ) ,
        ));
    }
}
