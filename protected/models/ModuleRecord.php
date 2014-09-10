<?php
class ModuleRecord extends MRecord {
    public $scondition = ' status=1 '; /*默认搜索条件*/
    protected $_bycu = true; //搜索自己
    
    public function init() {
        parent::init();
        if ($this->tableAlias && false) {
            $this->scondition = $this->tableAlias . $this->scondition;
        }
    }
    public function attributeLabels() {
        return array(
            '_startTime' => '开始时间',
            '_endTime' => '结束时间',
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        if ($this->getDefaultScopeDisabled()) {
            return array();
        }
        $arr = array();
        if (func_num_args() > 0 && func_get_arg(0)) {
            $arr['order'] = 'add_time DESC ';
        }
        $condition = array();
        if ($this->scondition && $this->scondition != '') {
            $condition[] = $this->scondition;
        }
        if ($this->hasAttribute('fromid')) {
            $condition[] = 'fromid=' . Tak::getFormid();
        }
        if ($this->getCu() && Tak::getManageid()) {
            $condition[] = 'manageid=' . Tak::getManageid();
        }
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    
    public function setGetCU($isTure = false) {
        $this->_bycu = $isTure;
        return $this;
    }
    protected function getCu() {
        $result = false;
        if ($this->hasAttribute('manageid') && $this->_bycu && !Tak::checkSuperuser()) {
            $result = true;
        }
        return $result;
    }
    
    public function scopes() {
        return array(
            'published' => array(
                'condition' => 'status=1',
            ) ,
            'recently1' => array(
                'order' => 'add_time DESC',
            ) ,
            'sort_time' => array(
                'order' => 'add_time DESC',
            )
        );
    }
    
    public function recently($limit = 5, $pcondition = false, $order = 'add_time DESC') {
        $condition = $this->defaultScope(false);
        if (is_array($condition) && $condition['condition']) {
            $condition = array(
                $condition['condition']
            );
        } else {
            $condition = array();
        }
        
        if (is_string($pcondition)) {
            $condition[] = $pcondition;
        } elseif (is_array($pcondition)) {
            $condition = array_merge_recursive($condition, $pcondition);
        }
        $_temps = array(
            'condition' => implode(" AND ", $condition) ,
        );
        if ($order) {
            $_temps['order'] = $order;
        }
        
        $criteria = new CDbCriteria($_temps);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $limit,
            ) ,
        ));
    }
    
    public function search() {
        $criteria = parent::search();
        $this->resetScope(false);
        return $criteria;
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Manage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    // 设置搜索
    public function setRecycle($status = 0) {
        $this->scondition = $this->getConAlias("status=$status");
        $this->resetScope(false);
    }
    // 还原
    public function setRestore() {
        $result = false;
        if ($this->status != TakType::STATUS_DEFAULT) {
            $this->status = TakType::STATUS_DEFAULT;
            if ($this->save()) {
                $result = true;
            } else {
                $arr = $this->getErrors();
            }
        }
        return $result;
    }
    //保存数据前
    protected function beforeSave() {
        $isok = func_num_args() > 0 && func_get_arg(0);
        $result = parent::beforeSave($isok);
        if (!$isok && $result) {
            //添加数据时候
            $arr = Tak::getOM();
            if ($this->isNewRecord) {
                // 导入客户时候，其他管理员可以选择业务员
                if ($this->hasAttribute('manageid') && !$this->manageid) {
                    $this->manageid = $arr['manageid'];
                }
                if ($this->hasAttribute('fromid')) {
                    $this->fromid = $arr['fromid'];
                }
                if (!$this->add_us) {
                    $this->add_us = $arr['manageid'];
                }
                if (!$this->add_time) {
                    $this->add_time = $arr['time'];
                }
                if (!$this->add_ip) {
                    $this->add_ip = $arr['ip'];
                }
            } else {
                //修改数据时候
                $this->modified_us = $arr['manageid'];
                $this->modified_time = $arr['time'];
                $this->modified_ip = $arr['ip'];
            }
        }
        return $result;
    }
    protected function beforeValidate() {
        $result = parent::beforeValidate();
        return $result;
    }
    protected function afterDelete() {
        $result = parent::afterDelete();
        return $result;
    }
    //
    protected function afterSave() {
        parent::afterSave();
        if (!$this->isLog) {
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
    
    public function del() {
        $result = false;
        if ($this->status != TakType::STATUS_DELETED) {
            $this->status = TakType::STATUS_DELETED;
            if ($this->save()) {
                $result = true;
            } else {
                $arr = $this->getErrors();
            }
        }
        return $result;
    }
    
    public function dels() {
        $result = false;
        if ($this->status != TakType::STATUS_DELETED) {
            $this->status = TakType::STATUS_DELETED;
            $this->save();
            $result = true;
        }
        return $result;
    }
}
