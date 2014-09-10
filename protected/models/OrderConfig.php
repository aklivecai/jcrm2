<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-04-24 08:30:11
 * @version $Id$
 */
class OrderConfig {
    public static function getNoteText($fid = false) {
        $model = self::getNote($fid);
        $result = $model != null ? $model->item_value : '';
        return $result;
    }
    public static function getNote($fid = false) {
        !$fid && $fid = Tak::getFormid();
        $model = Setting::getDefault('order-note', $fid);
        return $model;
    }
    public static function getListAlipay($fid = false) {
        !is_numeric($fid) && $fid = Tak::getFormid();
        $tags = array();
        $sql = "SELECT  title FROM :tabl WHERE fromid=:fromid AND type=':item'";
        $sql = strtr($sql, array(
            ':tabl' => Info::$table,
            ':fromid' => $fid,
            ':item' => 'order-alipay',
        ));
        // Tak::KD($sql);
        $data = Tak::getDb('db')->createCommand($sql)->queryScalar();
        $result = array();
        if ($data != '') {
            $result = explode(',', $data);
        }
        return $result;
    }
    public static function getAlipay($id, $fid = false) {
        $tags = array();
        $id < 1 && $fid = 0;
        $sql = "SELECT  i.title,c.content FROM :info i LEFT JOIN :data c  ON(i.itemid=c.itemid)   WHERE i.fromid=:fromid AND i.type=:item";
        $sql = strtr($sql, array(
            ':info' => Info::$table,
            ':data' => ContentData::$table,
            ':itemid' => $id,
        ));
        $arr = array(
            ':fromid' => $fid,
            ':item' => 'order-alipay',
        );
        
        $data = Tak::getDb('db')->createCommand($sql)->queryRow(true, $arr);
        if ($data) {
            $data['title'] = OrderType::item('pay_type', $id);
        }
        return $data;
    }
    
    public static function getSelectAlipay($fid = false, $label = false) {
        $tags = self::getListAlipay($fid);
        // Tak::KD($tags);
        if (count($tags) == 0) {
            $tags = self::getListAlipay(0);
        }
        $data = array();
        $types = OrderType::items('pay_type');
        foreach ($tags as $key => $value) {
            $data[$value] = $types[$value];
        }
        return $data;
    }
}
