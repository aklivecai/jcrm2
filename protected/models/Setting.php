<?php
class Setting extends CActiveRecord {
    public static $table = '{{setting}}';
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    public function init() {
        parent::init();
        $this->manageid = Tak::getManageid();
        $this->itemid = Tak::fastUuid();
    }
    public function rules() {
        return array(
            array(
                'itemid, manageid, item_value',
                'required'
            ) ,
            array(
                'itemid, manageid',
                'length',
                'max' => 25
            ) ,
            array(
                'item_key',
                'length',
                'max' => 100
            ) ,
            array(
                'itemid, manageid, item_key, item_value',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'item_value',
                'uhtml'
            ) ,
        );
    }
    public function uhtml($attribute, $params) {
        $this->item_value = Tak::uhtml($this->item_value);
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'manageid' => '会员ID',
            'item_key' => '键',
            'item_value' => '值',
        );
    }
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('manageid', $this->manageid);
        $criteria->compare('item_key', $this->item_key, true);
        $criteria->compare('item_value', $this->item_value, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    public function scopes() {
        return array(
            'published' => array(
                'condition' => 'manageid=' . Tak::getManageid() ,
            ) ,
            'public' => array(
                'condition' => 'manageid=' . $this->manageid,
            ) ,
        );
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function takSave() {
        $this->deleteAll(" manageid=:manageid AND item_key=:item_key", array(
            ':manageid' => $this->manageid,
            ":item_key" => $this->item_key
        ));
        return parent::save();
    }
    public function getThemes() {
        $sql = "item_key LIKE 'themeSettings_%'";
        $list = $this->published()->findAll($sql);
        return $list;
    }
    
    public function saveDefault($data) {
        if ($this->manageid == 0) {
            $this->manageid = Tak::getFormid();
            $this->itemid = Tak::fastUuid();
            $this->setIsNewRecord(true);
        }
        $this->item_value = $data['item_value'];
        return $this->save();
    }
    
    public static function getDefault($type, $mid = false) {
        !$mid && $mid = Tak::getManageid();
        //$sql = " SELECT * FROM :table WHERE  item_key=:key AND (manageid=:manageid OR manageid=1) ORDER BY manageid DESC";
        //$sql = " SELECT * FROM :table WHERE  ORDER BY manageid DESC";
        $criteria = new CDbCriteria;
        $sql = strtr("item_key=':key' AND (manageid=:manageid OR manageid=0) ", array(
            ':key' => $type,
            ':manageid' => $mid,
        ));
        // Tak::KD($sql);
        $criteria->addCondition($sql);
        $criteria->order = 'manageid DESC'; //排序条件
        $criteria->limit = 1; //取1条数据，如果小于0，则不作处理
        /*
        $data = array(
            ':table' => self::$table,
            ':key' => $type,
            ':manageid' => $mid,
        );        
         $result = Tak::getDb('db')->createCommand($sql)->queryRow(true, $data);
        */
        $result = self::model()->find($criteria);
        return $result;
    }
    
    public static function getListByName($key, $uid = false) {
        $sql = "SELECT item_key,item_value FROM :tableName WHERE manageid=:manageid AND  item_key LIKE :name";
        $sql = strtr($sql, array(
            ':tableName' => self::$table,
        ));
        !$uid && $uid = Tak::getManageid();
        $command = Tak::getDb('db')->createCommand($sql);
        $dataReader = $command->queryAll(true, array(
            ':name' => "$key%",
            ':manageid' => $uid,
        ));
        $tags = array();
        foreach ($dataReader as $row) {
            $tags[$row['item_key']] = $row['item_value'];
        }
        return $tags;
    }
    public static function getStocks($manageid = false) {
        $list = self::getListByName('stocks_', $manageid);
        return $list;
    }
    public static function setStocks($arr, $manageid = false) {
    }
}
