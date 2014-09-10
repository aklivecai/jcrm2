<?php
class ContentData extends CActiveRecord {
    public static $table = '{{data}}';
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    public function init() {
        parent::init();
    }
    public function rules() {
        return array(
            array(
                'content',
                'required'
            ) ,
            array(
                'itemid',
                'required',
                'on' => 'update'
            ) ,
            array(
                'itemid',
                'length',
                'max' => 25
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'content' => '值',
        );
    }
    public static function getOne($id) {
        $sql = sprintf('SELECT content FROM %s WHERE itemid=%s', self::$table, $id);
        $content = Tak::getDb('db')->createCommand($sql)->queryScalar();
        return $content;
    }
    public static function delOne($id) {
        $sql = sprintf('DELETE  FROM %s WHERE itemid=%s', self::$table, $id);
        $result = Tak::getDb('db')->createCommand($sql)->query();
        return $result;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            if ($this->isNewRecord && !$this->itemid) {
                $this->itemid = Tak::fastUuid();
            }
        }
        return $result;
    }
}
