<?php
class Category extends LRecord {
    private $scondition = false; /*默认搜索条件*/
    public static $table = '{{category}}';
    public static $models = array(
        'product' => '产品分类'
    );
    
    private $oldParentid = null;
    //是否永远读取最新的数据信息
    public static $isNew = false;
    
    private static $cates = array();
    private static function getCates($module) {
        if (self::$isNew || !isset(self::$cates[$module])) {
            $sql = "SELECT * FROM :table WHERE  fromid=:fromid AND module=':module' ORDER BY listorder DESC,catid ASC";
            // $sql = "SELECT * FROM :table WHERE  fromid=:fromid AND module=':module' ORDER BY catid DESC,listorder DESC";
            $sql = strtr($sql, array(
                ':table' => self::$table,
                ':module' => $module,
                ':fromid' => Tak::getFormid() ,
            ));
            // Tak::KD($sql);
            $tags = self::$db->createCommand($sql)->queryAll();
            $result = array();
            foreach ($tags as $key => $value) {
                $result[$value['catid']] = $value;
            }
            self::$cates[$module] = $result;
        }
        return self::$cates[$module];
    }
    
    public static function getName($catid, $type, $isall = false) {
        $data = self::getCates($type);
        $result = '';
        if (isset($data[$catid])) {
            if ($isall && $data[$catid]['arrparentid']) {
                $list = explode(",", $data[$catid]['arrparentid']);
                $arr = array();
                foreach ($list as $value) {
                    if (isset($data[$value])) {
                        $arr[] = $data[$value]['catename'];
                    }
                }
                $arr[] = $data[$catid]['catename'];
                $result = implode($isall, $arr);
            } else {
                $result = $data[$catid]['catename'];
            }
        }
        return $result;
    }
    public static function getProductName($catid, $isall = false) {
        return self::getName($catid, 'product', $isall);
    }
    public static function getCatsProduct() {
        return self::getCates('product');
    }
    
    public function setModel($module) {
        $module = strtolower($module);
        if (self::$models[$module]) {
            $this->module = $module;
        }
    }
    
    public static function getModel($module = false) {
        $module = strtolower($module);
        $result = isset(self::$models[$module]) ? $module : '';
        return $result ? $result : false;
    }
    
    public function getTypeName($module) {
        return self::getModel($module);
    }
    
    public function primaryKey() {
        return 'catid';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
        return array(
            array(
                'catid',
                'addNow',
                'on' => 'create'
            ) ,
            array(
                'arrparentid',
                'formCatid',
                'on' => 'create'
            ) ,
            array(
                'fromid',
                'autoID'
            ) ,
            array(
                'itemid, fromid, catename',
                'required'
            ) ,
            array(
                'level, parentid, child, listorder,itemid,fromid',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'parentid',
                'checkParentid',
                'on' => 'update'
            ) ,
            
            array(
                'catid, fromid',
                'length',
                'max' => 10
            ) ,
            array(
                'item',
                'length',
                'max' => 20
            ) ,
            array(
                'catename',
                'length',
                'max' => 50
            ) ,
            array(
                'arrparentid',
                'length',
                'max' => 255
            ) ,
            array(
                'catid, fromid, item, catename, level, parentid, arrparentid, child, arrchildid, listorder',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function checkParentid($attribute, $params) {
        if (strpos(',' . $this->arrchildid . ',', ',' . $this->parentid . ',') !== false) {
            $err = $this->getAttributeLabel($attribute) . '不允许选择子分类!';
            $this->addError($attribute, $err);
        } elseif ($this->parentid == $this->catid) {
            $err = $this->getAttributeLabel($attribute) . '不允许是分类自己!';
            $this->addError($attribute, $err);
        }
    }
    public function formCatid($attribute, $params) {
        $this->arrparentid = $this->catid;
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = array(
            'order' => 'listorder DESC'
        );
        $condition = array();
        $condition[] = 'fromid=' . Tak::getFormid();
        if ($this->module) {
            $condition[] = "module='" . $this->module . "'";
        }
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    
    public function attributeLabels() {
        return array(
            'catid' => '分类编号',
            'fromid' => '平台会员ID',
            'module' => '模块',
            'item' => '信息数量',
            'catename' => '分类名',
            'level' => '级别',
            'parentid' => '上级分类',
            'arrparentid' => '上级所有ID',
            'child' => '是否有子分类',
            'arrchildid' => '子分类所有ID',
            'listorder' => '排序',
        );
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('catename', $this->catename, 1);
        if ($this->parentid > 0) {
            $criteria->compare('parentid', $this->parentid);
        }
        $pageSize = 99;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ) ,
        ));
    }
    
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            if ($this->isNewRecord) {
            }
        }
        return $result;
    }
    
    public function setChangePid($pid) {
        $this->oldParentid = $pid;
    }
    
    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $CATEGORY = self::getCatsProduct();
            $catid = $this->catid;
            $arr = array(
                ':table' => self::$table,
                ':module' => $this->module,
                ':fromid' => Tak::getFormid() ,
                ':catid' => $catid,
            );
            $childs = '';
            $childs.= ',' . $catid;
            if ($this->parentid) {
                //update cure
                $CATEGORY[$catid] = $this->attributes;
                $arrparentid = self::get_arrparentid($catid, $CATEGORY);
                /*Tak::KD($arrparentid,1);*/
            } else {
                $arrparentid = 0;
            }
            $sql = "UPDATE :table SET arrparentid=':arrparentid' WHERE catid=:catid AND  fromid=:fromid AND module=':module' ";
            $arr[':arrparentid'] = $arrparentid;
            $sql = strtr($sql, $arr);
            self::$db->createCommand($sql)->execute();
            // Tak::KD($sql,1);
            if ($this->parentid) {
                $parents = array();
                $cid = $this->parentid;
                $parents[] = $cid;
                while (1) {
                    if ($CATEGORY[$cid]['parentid']) {
                        $parents[] = $cid = $CATEGORY[$cid]['parentid'];
                    } else {
                        break;
                    }
                }
                foreach ($parents as $catid) {
                    $arrchildid = $CATEGORY[$catid]['child'] ? $CATEGORY[$catid]['arrchildid'] . $childs : $catid . $childs;
                    $arr[':arrchildid'] = $arrchildid;
                    $arr[':catid'] = $catid;
                    $sql = "UPDATE :table SET child=1 , arrchildid=':arrchildid' WHERE  catid=:catid AND  fromid=:fromid AND module=':module'";
                    $sql = strtr($sql, $arr);
                    // Tak::KD($sql,1);
                    self::$db->createCommand($sql)->execute();
                }
            }
        } elseif ($this->oldParentid != null) {
            $CATEGORY = self::getCatsProduct();
            $sql = $this->catid;
            $temps = array(
                $this->oldParentid,
                $this->parentid
            );
            foreach ($temps as $value) {
                $sql.= "," . $value;
                if ($CATEGORY[$value]['arrparentid']) {
                    $sql.= "," . $CATEGORY[$value]['arrparentid'];
                }
            }
            if ($this->arrchildid) {
                $sql.= ',' . $this->arrchildid;
            }
            $data = explode(',', $sql);
            $data = array_unique($data);
            $data = array_filter($data);
            // Tak::KD($sql);
            // Tak::KD($data);
            if (count($data) > 0) {
                $sql = sprintf(' catid in (%s)', implode(',', $data));
            } else {
                $sql = '';
            }
            self::repair($this->module, $sql);
        }
    }
    
    public static function repair($module, $sql = false) {
        $_sqldata = array(
            ':table' => self::$table,
            ':module' => $module,
            ':fromid' => Tak::getFormid() ,
            ':where' => ""
        );
        if ($sql) {
            $_sqldata[':where'] = sprintf(" AND %s", $sql);
        }
        $sql = "SELECT * FROM :table WHERE fromid=:fromid AND module=':module' :where ORDER BY listorder,catid";
        
        $sql = strtr($sql, $_sqldata);
        // Tak::KD($sql);
        
        $comm = self::$db->createCommand();
        
        $data = $comm->setText($sql)->queryAll(true);
        
        $CATEGORY = array();
        foreach ($data as $r) {
            $CATEGORY[$r['catid']] = $r;
        }
        $childs = array();
        foreach ($CATEGORY as $catid => $category) {
            $CATEGORY[$catid]['arrparentid'] = $arrparentid = self::get_arrparentid($catid, $CATEGORY);
            $sql = "arrparentid='$arrparentid'";
            $_sqldata[':catid'] = $catid;
            $sql = "UPDATE :table SET $sql WHERE  catid=:catid AND  fromid=:fromid AND module=':module'";
            $sql = strtr($sql, $_sqldata);
            // Tak::KD($sql);
            $comm->setText($sql)->execute();
            
            if ($arrparentid) {
                $arr = explode(',', $arrparentid);
                foreach ($arr as $a) {
                    if ($a == 0) continue;
                    isset($childs[$a]) or $childs[$a] = '';
                    $childs[$a].= ',' . $catid;
                }
            }
        }
        foreach ($CATEGORY as $catid => $category) {
            if (isset($childs[$catid])) {
                $CATEGORY[$catid]['arrchildid'] = $arrchildid = $catid . $childs[$catid];
                $CATEGORY[$catid]['child'] = 1;
                $_sqldata[':catid'] = $catid;
                $sql = "UPDATE :table SET arrchildid='$arrchildid',child=1 WHERE  catid=:catid AND  fromid=:fromid AND module=':module'";
                $sql = strtr($sql, $_sqldata);
                // Tak::KD($sql);
                $comm->setText($sql)->execute();
            } else {
                $CATEGORY[$catid]['arrchildid'] = $catid;
                $CATEGORY[$catid]['child'] = 0;
                $_sqldata[':catid'] = $catid;
                $sql = "UPDATE :table SET arrchildid='$catid',child=0 WHERE  catid=:catid AND  fromid=:fromid AND module=':module'";
                $sql = strtr($sql, $_sqldata);
                // Tak::KD($sql);
                $comm->setText($sql)->execute();
            }
        }
        // exit;
        return true;
    }
    
    public function isDel() {
        $data = self::getCates($this->module);
        // Tak::KD($data);
        $ids = self::get_arrchildid($this->catid, $data);
        $sql = " SELECT count(s.itemid) FROM :table  AS s
                      WHERE typeid in(:ids) ";
        $sql = strtr($sql, array(
            ':table' => Product::$table,
            ':fromid' => Tak::getFormid() ,
            ':ids' => $ids,
        ));
        // Tak::KD($sql,1);
        $count = self::$db->createCommand($sql)->queryScalar();
        // Tak::KD($count);
        // exit;
        return $count;
    }
    
    public function counts() {
        $sql = " SELECT count(catid) FROM :table 
                  WHERE fromid = :fromid AND module=':module'";
        $sql = strtr($sql, array(
            ':table' => self::$table,
            ':module' => $this->module,
            ':fromid' => Tak::getFormid() ,
        ));
        $count = self::$db->createCommand($sql)->queryScalar();
        return $count;
    }
    
    public function del() {
        $result = false;
        $count = $this->isDel();
        if ($count > 0) {
            $result = '分类下已经有产品，不能进行删除';
        } elseif ($this->counts() == 1) {
            $result = '最后一个分类不允许删除!';
        } else {
            if ($this->child) {
                $data = self::getCates($this->module);
                $ids = self::get_arrchildid($this->catid, $data);
                $this->deleteAll("catid in($ids)");
            } else {
                $this->delete();
            }
            $CATEGORY = self::getCatsProduct();
            $sql = $this->catid;
            $sql.= "," . $this->parentid;
            if ($CATEGORY[$this->parentid]['arrparentid']) {
                $sql.= "," . $CATEGORY[$this->parentid]['arrparentid'];
            }
            $data = explode(',', $sql);
            $data = array_unique($data);
            $data = array_filter($data);
            // Tak::KD($sql);
            // Tak::KD($data);
            if (count($data) > 0) {
                $sql = sprintf(' catid in (%s)', implode(',', $data));
            } else {
                $sql = '';
            }
            self::repair($this->module, $sql);
        }
        return $result;
    }
    
    public static function get_arrparentid($catid, $CATEGORY) {
        if ($CATEGORY[$catid]['parentid'] && $CATEGORY[$catid]['parentid'] != $catid) {
            $parents = array();
            $cid = $catid;
            while ($catid) {
                if ($CATEGORY[$cid]['parentid']) {
                    $parents[] = $cid = $CATEGORY[$cid]['parentid'];
                } else {
                    break;
                }
            }
            $parents[] = 0;
            return implode(',', array_reverse($parents));
        } else {
            return '0';
        }
    }
    public static function get_arrchildid($catid, $CATEGORY) {
        $arrchildid = '';
        foreach ($CATEGORY as $category) {
            if (strpos(',' . $category['arrparentid'] . ',', ',' . $catid . ',') !== false) $arrchildid.= ',' . $category['catid'];
        }
        return $arrchildid ? $catid . $arrchildid : $catid;
    }
    
    public static function toHtmlSelect() {
        $tree = new JTree;
        $tree->JTree($categorys);
        $content = $tree->get_tree(0, "<option value=\\\"\$id\\\">\$spacer\$name</option>") . '</select>';
        Tak::KD($content);
        /*cache_write('catetree-'.$moduleid.'.php', $content);*/
    }
}
