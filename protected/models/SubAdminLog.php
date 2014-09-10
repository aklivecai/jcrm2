<?php
class SubAdminLog extends AdminLog {
    public $users = null;
    public function setUsers($arr) {
        $this->users = $arr;
        return $this;
    }
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $mid = $this->manageid;
        $keys = array_keys($this->users);
        if ($mid > 0 && isset($this->users[$mid])) {
            $criteria->compare('manageid', $mid);
        } else {
            $criteria->addInCondition('manageid', $keys);
        }
        return $cActive;
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
