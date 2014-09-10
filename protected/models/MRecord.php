<?php
class MRecord extends CActiveRecord {
    public $mName = ''; /*当前类名字*/
    public $sName = ""; /*显示名字*/
    
    public $isLog = true; /*是否记录日志*/
    
    private $_defaultScopeDisabled = false; /* Flag - whether defaultScope is disabled or not*/
    
    protected $tableName = ''; /*表名*/
    
    public $linkName = null; /*连接的显示的字段名字*/
    
    public function init() {
        $this->mName = get_class($this);
        $this->sName = Tk::g($this->mName);
        // Tak::KD(self::$db);
        // echo '<div style="display:none;">';
        // print_r(self::$db);
        // echo '</div>';
        
        
    }
    
    public static $table = null;
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    
    public function primaryKey() {
        return 'itemid';
    }
    
    protected function setItemid($value) {
        if ($this->hasAttribute('itemid')) {
            $this->itemid = $value;
        } else {
            $primary = $this->primaryKey();
            $this->{$primary} = $value;
        }
        return $this;
    }
    
    public function disableDefaultScope() {
        $this->_defaultScopeDisabled = true;
        return $this;
    }
    
    public function getConAlias($sql) {
        if (property_exists($this, 'tableAlias') && $this->tableAlias) {
            $alias = $this->tableAlias;
            $sql = $alias . '.' . $sql;
        }
        return $sql;
    }
    
    public function getDefaultScopeDisabled() {
        return $this->_defaultScopeDisabled;
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
    
    public function setCriteriaTime(&$criteria, $cols) {
        $arr = is_array($cols) ? $cols : array(
            $cols
        );
        
        foreach ($arr as $col) {
            
            $value = $this[$col];
            if ($value) {
                if ($value > 0 && Tak::isTimestamp($value)) {
                    $criteria->compare($col, $value);
                } elseif ($value == 0) {
                } else {
                    $start = strtotime($value);
                    $end = TaK::getDayEnd($start);
                    $criteria->addBetweenCondition($col, $start, $end);
                }
            }
        }
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function checkTime($attribute, $params) {
        $time = $this->$attribute;
        if (!$time) {
            $this->$attribute = 0;
            return true;
        }
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }
        $time = Tak::isTimestamp($time);
        if ($time) {
            $this->$attribute = $time;
        } else {
            $this->addError($attribute, '联系时间错误！');
        }
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
        }
        if ($this->hasAttribute('manageid')) {
            $sql[] = 'manageid = :manageid';
            $arr[':manageid'] = $this->manageid ? $this->manageid : Tak::getManageid();
        }
        
        $sql = implode(' AND ', $sql);
        // Tak::KD(strtr($sql,$arr),1);
        // Tak::KD($arr,1);
        // 查找满足指定条件的结果中的第一行
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        // Tak::KD($m,1);
        $result = true;
        if ($m != null) {
            $err = $this->getAttributeLabel($attribute) . ' 已经存在 :';
            $err.= $m->getHtmlLink();
            $this->addError($attribute, $err);
            $result = false;
        }
        return $result;
    }
    
    public static function getOne($itemid = false) {
        if (!$itemid) {
            $itemid = Tak::getManageid();
        }
        return self::model()->findByPk($itemid);
    }
    //保存数据前
    protected function beforeSave() {
        $isok = func_num_args() > 0 && func_get_arg(0);
        $result = parent::beforeSave();
        if (!$isok && $result) {
            //添加数据时候
            if ($this->isNewRecord) {
                if (!$this->primaryKey) {
                    $this->setItemid(Tak::fastUuid());
                } elseif ($this->primaryKey == 1) {
                    $this->setItemid(null);
                }
            } else {
            }
        }
        return $result;
    }
    protected function beforeValidate() {
        $result = parent::beforeValidate();
        return $result;
    }
    //
    protected function afterSave() {
        parent::afterSave();
    }
    
    protected function afterDelete() {
        $result = parent::afterDelete();
        if ($result && $this->isLog) {
            $this->logDel();
        }
        return $result;
    }
    
    protected function getDefaultScopeSql() {
        $sqlWhere = $this->defaultScope(0);
        if (is_array($sqlWhere) && $sqlWhere['condition']) {
            $sqlWhere = is_array($sqlWhere['condition']) ? implode(' AND ', $sqlWhere['condition']) : $sqlWhere['condition'];
        } else {
            $sqlWhere = '';
        }
        return $sqlWhere;
    }
    
    protected function logDel() {
        AdminLog::log(Tk::g('Delete') . $this->sName);
    }
    
    public function getIData($col = 'add_time', $dtime = false, $swhere = false) {
        $sqlWhere = $this->getDefaultScopeSql();
        if (!$dtime) {
            $dtime = Tak::getDateTop();
        }
        $arrSql = array(
            'SELECT COUNT(:itemid) AS num, \'allData\' AS ikey FROM :tableName WHERE :sqlWhere '
        );
        foreach ($dtime as $key => $value) {
            $sql = ' SELECT COUNT(:itemid) AS num, \'' . $key . 'Data\' AS ikey FROM  :tableName WHERE :sqlWhere AND :col BETWEEN ' . $value['start'] . ' AND ' . $value['end'];
            $arrSql[] = $sql;
        }
        $sql = implode(' UNION ALL ', $arrSql);
        $sql = strtr($sql, array(
            ':tableName' => $this->tableName() ,
            ':yea' => $dtime['y']['start'],
            ':yeaend' => $dtime['y']['end'],
            ':month' => $dtime['m']['start'],
            ':monthend' => $dtime['m']['end'],
            ':day' => $dtime['d']['start'],
            ':dayend' => $dtime['d']['end'],
            ':sqlWhere' => $sqlWhere,
            ':col' => $col,
            ':itemid' => $this->primaryKey()
        ));
        // Tak::KD($sql,1);
        $command = self::$db->createCommand($sql);
        $dataReader = $command->query();
        $tags = array();
        foreach ($dataReader as $row) {
            $tags[$row['ikey']] = $row['num'];
        }
        // Tak::KD($tags,1);
        return $tags;
    }
    
    public function getLinkName($key = false) {
        $result = '';
        $key = $key ? $key : $this->linkName;
        if ($key !== null) {
            if (!is_array($key)) {
                $key = array(
                    $key
                );
            }
            $t = array();
            foreach ($key as $k1 => $v1) {
                if ($this->hasAttribute($v1)) {
                    $t[] = $this->$v1;
                }
            }
            $result = implode('-', $t);
        }
        return $result;
    }
    
    public function getLink($itemid = false, $action = 'view') {
        if (!$itemid) {
            $itemid = $this->primaryKey;
        }
        $link = Yii::app()->createUrl(strtolower($this->mName) . '/' . $action, array(
            'id' => $itemid
        ));
        return $link;
    }
    public function getHtmlLink($name = false, $itemid = false, array $htmlOptions = array() , $action = 'view') {
        if (!$name) {
            $name = $this->getLinkName();
        }
        $link = CHtml::link($name, $this->getLink($itemid, $action) , $htmlOptions);
        return $link;
    }
    public function getHtmlPreviewLink($name = false, $itemid = false, $htmlOptions = array()) {
        $link = $this->getHtmlLink($name, $itemid, $htmlOptions, 'preview');
        return $link;
    }
    
    protected function getOjb($opt = 'next', $isid = false) {
        $m = $this->mName;
        $result = null;
        $arr = array(
            'pre' => array(
                'opt' => '<',
                'order' => 'DESC'
            ) ,
            'next' => array(
                'opt' => '>',
                'order' => 'ASC'
            ) ,
        );
        if ($this->primaryKey > 0 && isset($arr[$opt])) {
            $t = $arr[$opt];
        } else {
            return null;
        }
        $col = $isid ? ':itemid' : '*';
        $sqlWhere = array(
            $this->getDefaultScopeSql()
        );
        $sqlWhere[] = ':itemid :opt :current_id';
        $sqlWhere = array_filter($sqlWhere);
        $sqlWhere = implode(" AND ", $sqlWhere);
        
        $sql = "SELECT $col FROM :tableName WHERE $sqlWhere ORDER BY :itemid :order ";
        // LIMIT 1
        
        $sql = strtr($sql, array(
            ':tableName' => $this->tableName() ,
            ':sqlWhere' => $sqlWhere,
            ':itemid' => $this->primaryKey() ,
            ':current_id' => $this->primaryKey,
            ':opt' => $t['opt'],
            ':order' => $t['order']
        ));
        $dataReader = self::$db->createCommand($sql)->queryRow();
        if (count($dataReader) > 0) {
            $result = $dataReader;
        }
        return $result;
    }
    
    protected function getNPList($isid = false, $top = 10) {
        $result = false;
        $arrSql = array();
        $_arr = array(
            'Pre' => array(
                'opt' => '<',
                'order' => 'DESC'
            ) ,
            'Next' => array(
                'opt' => '>',
                'order' => 'ASC'
            ) ,
        );
        if ($top > 1) {
            $_arr['Next']['order'] = 'ASC';
        }
        $tags = array();
        $col = $isid ? ':itemid' : '*';
        // $sqlWhere = array_filter($sqlWhere);
        $sql1 = ':itemid :opt :current_id';
        $sqlWhere = array(
            $this->getDefaultScopeSql()
        );
        // Tak::KD($sqlWhere);
        // Tak::KD($this->getCu());
        // Tak::KD($this->_bycu);
        
        foreach ($_arr as $key => $value) {
            $sqlWhere['w'] = str_replace(':opt', $value['opt'], $sql1);
            $_sqlWhere = implode(" AND ", $sqlWhere);
            $arrSql[] = " SELECT * FROM (SELECT $col,'$key' AS `ikey` FROM :tableName WHERE $_sqlWhere ORDER BY :itemid {$value['order']} LIMIT $top) AS `$key` ";
            $tags[$key] = array();
        }
        
        $sql = implode(' UNION ALL ', $arrSql);
        
        $sql = strtr($sql, array(
            ':tableName' => $this->tableName() ,
            ':itemid' => $this->primaryKey() ,
            ':current_id' => $this->primaryKey
        ));
        
        if ($this->tableAlias) {
            $sql = str_replace(' ' . $this->tableAlias . '.', ' ', $sql);
        }
        $dataReader = self::$db->createCommand($sql)->query();
        
        foreach ($dataReader as $row) {
            $tags[$row['ikey']][$row[$this->primaryKey() ]] = $row;
        }
        $tags = array_filter($tags);
        
        if (count($tags) > 0) {
            $result = $tags;
        }
        return $result;
    }
    
    public function getNext($isid) {
        $result = $this->getOjb('next', $isid);
        if ($result && $isid) {
            $result = current($result[0]);
        }
        return $result;
    }
    
    public function getPrevious($isid) {
        $result = $this->getOjb('pre');
        if ($result && $isid) {
            $result = current($result[0]);
        }
        return $result;
    }
    
    public function getNP($isid = true, $top = 1) {
        $top = (int)$top > 0 ? $top : 1;
        $result = array();
        $tags = $this->getNPList($isid, $top);
        if ($tags && count($tags) > 0) {
            if ($top == 1 && $isid) {
                foreach ($tags as $key => $value) {
                    $tags[$key] = current(current($value));
                }
            } else {
                foreach ($tags as $key => $value) {
                    $temp = $value;
                    // arsort($temp);
                    ksort($temp);
                    $tags[$key] = $temp;
                }
                // Tak::KD($tags,1);
                
                
            }
            $result = $tags;
        }
        return $result;
    }
}
