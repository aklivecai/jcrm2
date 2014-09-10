<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-11 11:52:21
 * @version 车间
 */
class Workshop {
    private static $db = null;
    private static $data = null;
    private static $workshop = null;
    private static $process = null;
    
    public static function init() {
        self::getDb();
        self::getPar();
    }
    
    public static function getDb() {
        if (self::$db == null) {
            self::$db = Ak::getDb('db');
        }
        return self::$db;
    }
    public static function getPar() {
        if (self::$data == null) {
            self::$data = array(
                ':table' => '{{type}}',
                ':fromid' => Ak::getFormid() ,
            );
        }
        return self::$data;
    }
    /** 查询所有列表
     * @param  integer $type 类型编号
     * @return [type]        [description]
     */
    public static function getAll() {
        $workshop = self::getAllWorkshop();
        $process = array();
        $temps = self::getAllProcess();
        foreach ($temps as $key => $value) {
            if (!isset($workshop[$value['item']]['process'])) {
                $workshop[$value['item']]['process'] = array();
            }
            $workshop[$value['item']]['process'][$value['typeid']] = $value;
        }
        // Tak::KD($workshop,1);
        return $workshop;
        $data = array(
            'process' => array_keys($process) ,
            'workshop' => $workshop,
        );
        return $data;
    }
    public static function getAllWorkshop($isnew = false) {
        if (self::$workshop == null || $isnew) {
            $sql = "SELECT typeid,typename FROM :table WHERE fromid=:fromid AND item = 'workshop' ORDER BY typeid  ASC ";
            $db = self::getDb();
            $data = self::getPar();
            $sql = strtr($sql, $data);
            // Tak::KD($sql);
            $data = $db->createCommand($sql)->queryAll();
            $result = array();
            foreach ($data as $key => $value) {
                $result[$value['typeid']] = $value;
            }
            self::$workshop = $result;
        }
        return self::$workshop;
    }
    public static function getAllByProcess($typeid) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "SELECT typeid,typename,item FROM :table 
            WHERE fromid=:fromid 
                AND item =:item
            ORDER BY  listorder  ASC";
        $data[':item'] = $typeid;
        $sql = strtr($sql, $data);
        $tags = $db->createCommand($sql)->queryAll();
        return $tags;
    }
    public static function getAllProcess($isover = false) {
        if (self::$process == null || $isove) {
            $workshop = array_keys(self::getAllWorkshop());
            if (count($workshop) == 0) {
                return array();
            }
            $sql = "SELECT typeid,typename,item FROM :table 
            WHERE fromid=:fromid 
                AND item IN(:keys)
            ORDER BY  listorder  ASC";
            $db = self::getDb();
            $data = self::getPar();
            $data[':keys'] = implode(',', $workshop);
            $sql = strtr($sql, $data);
            $tags = $db->createCommand($sql)->queryAll();
            $result = array();
            foreach ($tags as $key => $value) {
                $result[$value['typeid']] = $value;
            }
            self::$process = $result;
        }
        return self::$process;
    }
    /** 删除工序
     * @param  int $id   工序编号
     * @param  int $item 车间编号
     * @return [type]       [description]
     */
    public static function delProcess($id, $item) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "DELETE FROM :table WHERE fromid=:fromid AND typeid=:typeid AND item=:item";
        $sql = strtr($sql, $data);
        $command = $db->createCommand($sql);
        $command->bindParam(":item", $item, PDO::PARAM_STR);
        $command->bindParam(":typeid", $id, PDO::PARAM_STR);
        $result = $command->execute();
        return $result == 1;
    }
    /** 删除车间,清空该车间下的工序
     * @param  int $id 车间编号
     * @return [type]     [description]
     */
    public static function delWorkshop($id) {
        $db = self::getDb();
        $data = self::getPar();
        $sqls = array(
            "DELETE FROM :table WHERE fromid=:fromid AND typeid=:typeid AND item='workshop'",
            'DELETE FROM :table WHERE fromid=:fromid  AND item=:typeid'
        );
        $result = 0;
        foreach ($sqls as $key => $value) {
            $sql = strtr($value, $data);
            $command = $db->createCommand($sql);
            $command->bindParam(":typeid", $id, PDO::PARAM_INT);
            $result+= $command->execute();
        }
        return $result > 0;
    }
    /** 添加工序
     * @param int $typeid 车间编号
     * @param string $name   工序名
     */
    public static function addProcess($typeid, $name) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "INSERT INTO :table (typeid, fromid,typename,item,listorder) VALUES(:typeid, :fromid,:typename,:item,:listorder)";
        $uid = $data[':listorder'] = $data[':typeid'] = Ak::getTimeId();
        $sql = strtr($sql, $data);
        // Tak::KD($sql,1);
        $command = $db->createCommand($sql);
        $command->bindParam(":item", $typeid, PDO::PARAM_STR);
        $command->bindParam(":typename", $name, PDO::PARAM_STR);
        $result = $command->execute();
        return $result == 1 ? array(
            'itemid' => $uid,
            'name' => $name
        ) : false;
    }
    /** 更新工序
     * @param  int $typeid 工序编号
     * @param  string $name   工序名
     * @param  int $item   车间编号
     * @return [type]         [description]
     */
    public static function upProcess($typeid, $name, $item) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "UPDATE :table SET typename=:typename WHERE fromid=:fromid AND typeid=:typeid AND item=:item";
        $sql = strtr($sql, $data);
        $command = $db->createCommand($sql);
        $command->bindParam(":typeid", $typeid, PDO::PARAM_STR);
        $command->bindParam(":item", $item, PDO::PARAM_STR);
        $command->bindParam(":typename", $name, PDO::PARAM_STR);
        $result = $command->execute();
        return true;
    }
    /** 添加车间
     * @param string $name 车间名
     */
    public static function addWorkshop($name) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "INSERT INTO :table (typeid, fromid,typename,item,listorder) VALUES(:typeid, :fromid,:typename,'workshop',:listorder)";
        $uid = $data[':listorder'] = $data[':typeid'] = Ak::getTimeId();
        $sql = strtr($sql, $data);
        $command = $db->createCommand($sql);
        $command->bindParam(":typename", $name, PDO::PARAM_STR);
        $result = $command->execute();
        return $result == 1 ? array(
            'itemid' => $uid,
            'name' => $name
        ) : false;
    }
    /** 更新工序排序
     * @param  array $data   工序数组
     * @param  int $typeid 车间编号
     * @return [type]         [description]
     */
    public static function orderWorkshop($tags, $typeid) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "DELETE FROM :table WHERE fromid=:fromid  AND item=:typeid";
        $sql = strtr($sql, $data);
        // Tak::KD($sql);
        $command = $db->createCommand($sql);
        $command->bindParam(":typeid", $typeid, PDO::PARAM_INT);
        $result = $command->execute();
        if ($result > 0) {
            $sqls = array();
            $num = 0;
            $data[':item'] = $typeid;
            foreach ($tags as $key => $value) {
                $num+= 10;
                $sqls[] = "($key, :fromid, '$value', ':item', $num)";
            }
            $sql = "INSERT INTO tak_type (typeid, fromid, typename, item, listorder) VALUES ";
            $sql.= implode(',', $sqls);
            $sql.= ';';
            $sql = strtr($sql, $data);
            // Tak::KD($sql, 1);
            $command = $db->createCommand($sql);
            $result+= $command->execute();
        }
        return $result;
    }
    /**
     * 查看车间编号是否在生产中有记录
     * @param  int $itemid 车间编号
     * @return [type]         [description]
     */
    public static function inProduction($itemid) {
        $db = self::getDb();
        $data = self::getPar();
        $sql = "SELECT 1 FROM :table WHERE fromid=:fromid  AND workshop_id=:itemid ";
        $data[':table'] = ProductionDays::$table;
        $sql = strtr($sql, $data);
        // Tak::KD($sql);
        $command = $db->createCommand($sql);
        $command->bindParam(":itemid", $itemid, PDO::PARAM_INT);
        $row = $command->queryScalar();
        return $row > 0;
    }
}
