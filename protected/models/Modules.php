<?php
class Modules extends CActiveRecord {
    public static $table = '{{Modules}}';
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function rules() {
        return array(
            array(
                'module,name',
                'required'
            ) ,
            array(
                'name',
                'checkRepetition'
            ) ,
            array(
                'status,listorder',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'module, name, note, status, listorder',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'moduleid' => '编号',
            'module' => '模块',
            'name' => '模块名称',
            'installtime' => '安装时间',
            'last_time' => '最后更新时间',
            'listorder' => '排序',
            'status' => ' 状态', /*(0:禁用,1:正常,2:维护ing)*/
            'note' => '介绍',
        );
    }
    public function checkRepetition($attribute, $params) {
        $sql = array(
            "LOWER(:col)=:val"
        );
        $arr = array(
            ':col' => $attribute,
        );
        if ($this->primaryKey > 0) {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = 'moduleid';
            $arr[':itemid'] = $this->primaryKey;
        }
        
        $sql = implode(' AND ', $sql);
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        if ($m != null) {
            $err = $this->getAttributeLabel($attribute) . ' 已经存在 :';
            $err.= $m->getHtmlLink();
            $this->addError($attribute, $err);
        }
    }
    public function search() {
        $criteria = new CDbCriteria;
        $pageSize = 100;
        $colV = Yii::app()->request->getQuery('dt', false);
        if ($colV && $colV != '' && isset($_GET['col']) && $this->hasAttribute($_GET['col'])) {
            $date = Tak::searchData($colV);
            if ($date) {
                $criteria->addBetweenCondition($_GET['col'], $date['start'], $date['end']);
            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ) ,
        ));
    }
}
