<?php
class TestCompany extends Jb2bCompany {
    public static $types = array(
        '0' => '已激活',
        '1' => '全部',
        '11' => '未激活',
        '2' => '已过期',
        '3' => '未过期',
    );
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $get = Yii::app()->request->getQuery('t', 0);
        $indata = array();
        $notdata = array();
        if ($get == 1) {
        } else {
            $data = self::getMs();
            if ($get == 0) {
                $condition = array(
                    'start_time>0'
                );
                $criteria->addInCondition('userid', array_keys($data));
            } elseif ($get == 11) {
                $criteria->addNotInCondition('userid', array_keys($data));
            }
            if ($get == 2 || $get == 3) {
                $condition = array(
                    'active_time>0'
                );
                $active_time = Tak::now();
                $t = mktime(23, 59, 59, date("m", $active_time) , date("d", $active_time) - 15, date("Y", $active_time));
                if ($get == 2) {
                    $condition[] = "active_time<$t";
                } else {
                    $condition[] = "active_time>0$t";
                }
                $data = self::getMs(implode(' AND ', $condition));
                $criteria->addInCondition('userid', array_keys($data));
            }
        }
        return $cActive;
    }
    
    private $m = null;
    
    public function getM() {
        if ($this->m == null) {
            $this->m = TestMemeber::model()->findByPk($this->userid);
        }
        return $this->m;
    }
    public function getTime() {
        $m = $this->getM();
        $active_time = $m->active_time;
        $start_time = $m->start_time;
        if ($active_time > 0) {
            $strs = array();
            
            if (Tak::isDayOver($active_time, 15)) {
                $strs[] = '<u>已过期</u>';
            }
            if ($m->start_time > 0) {
                $strs[] = sprintf(' 激活时间 :%s', Tak::timetodate($start_time, 5));
            }
            
            if ($active_time != $start_time) {
                $strs[] = sprintf(' 计算日期 :%s', Tak::timetodate($active_time, 5));
            }
            $e1 = mktime(23, 59, 59, date("m", $active_time) , date("d", $active_time) + 15, date("Y", $active_time));
            $strs[] = sprintf('过期时间 :%s', Tak::timetodate($e1, 5));
            echo implode('<br />', $strs);
        } else {
            echo '未激活';
        }
    }
    
    public function getLinks() {
        $str = array();
        $m = $this->getM();
        $active_time = $m->active_time;
        if ($active_time > 0) {
            $str[] = CHtml::link('日志', array(
                "/juren/testLog/admin",
                "TestLog[fromid]" => $m->itemid
            ));
            
            $str[] = CHtml::link('查看', array(
                "/juren/testMemeber/view",
                "id" => $m->itemid
            ));
        } else {
            $str[] = CHtml::link(Tk::g('Import') , array(
                "/juren/Company/import",
                "id" => $this->userid
            ));
        }
        
        return implode(' , ', $str);
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getMs($condition = false) {
        $sql = sprintf('SELECT *  FROM %s WHERE itemid BETWEEN 3 AND 10000 ', TestMemeber::$table);
        if ($condition) {
            $sql.= ' AND ' . $condition;
        }
        $tags = Tak::getDb('db')->createCommand($sql)->query()->readAll();
        $result = array();
        foreach ($tags as $key => $value) {
            $result[$value['itemid']] = $value;
        }
        return $result;
    }
}
