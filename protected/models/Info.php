<?php
class Info extends CActiveRecord {
    public static $table = '{{info}}';
    public $content = null;
    private $_iscontent = false;
    public function setIsContent($status) {
        $this->_iscontent = $status;
    }
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
                'title,type',
                'required'
            ) ,
            array(
                'itemid, fromid',
                'required',
                'on' => 'update'
            ) ,
            array(
                'itemid,fromid,',
                'length',
                'max' => 25
            ) ,
            array(
                'itemid,fromid,listorder',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'type',
                'length',
                'max' => 20
            ) ,
            array(
                'title',
                'length',
                'max' => 225
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'title' => '标题',
            'type' => '类型',
            'listorder' => '排序',
        );
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            if ($this->isNewRecord && $this->itemid <= 0) {
                $this->itemid = Tak::fastUuid();
            }
            if ($this->fromid <= 0) {
                $this->fromid = Tak::getFormid();
            }
        }
        
        return $result;
    }
    
    protected function afterFind() {
        parent::afterFind();
    }
    
    public static function getOne($id, $setIsContent = false) {
        $model = self::model()->findByPk($id);
        if ($model != null && $setIsContent) {
            $model->content = ContentData::getOne($model->itemid);
        } else {
            // $model = null;
        }
        return $model;
    }
    
    protected function afterSave() {
        parent::afterSave();
        if ($this->_iscontent == true) {
            $m = ContentData::model()->findByPk($this->itemid);
            if ($m == null) {
                $m = new ContentData();
                $m->itemid = $this->itemid;
            }
            $m->content = $this->content;
            $m->save();
        }
    }
    protected function afterDelete() {
        parent::afterDelete();
        ContentData::model()->deleteByPk($this->itemid);
        return $result;
    }
}
