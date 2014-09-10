<?php
class Test9Memeber extends TestMemeber {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $condition = array();
        if (isset($arr['condition'])) {
            $condition[] = $arr['condition'];
        }
        if (!Tak::checkSuperuser()) {
            $condition[] = "manageid=" . Tak::getManageid();
        }
        $get = Yii::app()->request->getQuery('t', false);
        
        if ($get) {
            $condition[] = "active_time>0";
            if ($get == 1) {
                $active_time = Tak::now();
                $t = mktime(23, 59, 59, date("m", $active_time) , date("d", $active_time) - 15, date("Y", $active_time));
                $condition[] = "active_time<$t";
            }
        }
        // $condition[] = 'display>0';
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    
    public function upPKey($v1) {
        $sql = 'UPDATE :table  SET :key=:v1 WHERE :key=:v2';
        $sql = strtr($sql, array(
            ':table' => self::$table,
            ':key' => $this->primaryKey() ,
            ':v1' => $v1,
            ':v2' => $this->primaryKey,
        ));
        // Tak::KD($sql,1);
        self::$db->createCommand($sql)->execute();
    }
    
    public function moveManage($manageid) {
        $sql = 'UPDATE :table  SET manageid=:v1 WHERE :key=:v2';
        $sql = strtr($sql, array(
            ':table' => self::$table,
            ':v1' => $manageid,
            ':key' => $this->primaryKey() ,
            ':v2' => $this->primaryKey,
        ));
        // Tak::KD($sql,1);
        self::$db->createCommand($sql)->execute();
    }
    
    public function getItems($top = 5) {
        $_tags = array();
        $nps = $this->getNP(true);
        foreach ($nps as $key => $value) {
            $_tags[] = array(
                'label' => Tk::g($key) ,
                'url' => array(
                    '/juren/default/email',
                    "itemid" => $value
                ) ,
            );
        }
        return $_tags;
        
        $tags = $this->getNPList(1, $top);
        $keys = array();
        foreach ($tags as $key => $value) {
            $keys = array_merge_recursive($keys, array_keys($value));
        }
        if (count($keys) == 0) {
            return $keys;
        }
        $sql = sprintf('itemid in (%s)', implode(',', $keys));
        $tags = $this->findAll($sql);
        
        foreach ($tags as $m) {
            $label = $m->company;
            $_tags[] = array(
                'label' => $label,
                'url' => array(
                    '/juren/default/email',
                    "itemid" => $m->primaryKey
                ) ,
            );
        }
        return $_tags;
    }
}
