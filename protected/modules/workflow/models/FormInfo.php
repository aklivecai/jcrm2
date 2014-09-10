<?php
/**
 * 表单的基本信息
 */
class FormInfo extends DbiRecod {
    public static $table = 'oawork_form_info';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'form_id';
    }
    public function rules() {
        return array(
            array(
                'form_id,form_name',
                'required'
            ) ,
            array(
                'form_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'form_id,form_name,forhtml',
                'safe',
            ) ,
        );
    }
    public function relations() {
        return array(
            'formField' => array(
                self::HAS_MANY,
                'FormField',
                'form_id',
                'order' => 'field_id asc',
                'select' => 'field_name,field_type,field_value,field_default'
            ) ,
            'flowInfo' => array(
                self::HAS_MANY,
                'FlfowInfo',
                'form_id'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'form_name' => '表单名称',
            'forhtml' => 'HTML内容',
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $condition = array();
        $condition[] = 'fromid=' . Ak::getFormid();
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($this->isNewRecord) {
            $this->fromid = Ak::getFormid();
        }
        return $result;
    }
    /**
     * 查看表单的所有字段
     * @param  integer $ids [description]
     * @param  integer $id  [description]
     * @return [type]       [description]
     */
    public function getFields($ids = 0, $id = 0) {
        $id == 0 && $id = $this->primaryKey;
        $sql = 'form_id=:form_id';
        $data = array(
            ':form_id' => $id
        );
        if ($ids && is_array($ids)) {
            $sql.= ' AND field_id IN(:ids)';
            $data[':ids'] = implode(',', $ids);
        }
        $sql = strtr($sql, $data);
        $fields = FormField::model()->findAll($sql);
        return $fields;
    }
    public function getFieldsBySql($id = 0, $key = false) {
        $id == 0 && $id = $this->primaryKey;
        $data = array(
            ':table' => FormField::$table,
            ':fid' => $id,
        );
        $sql = "SELECT field_id,field_name,otype FROM :table  WHERE form_id=:fid ";
        $sql = strtr($sql, $data);
        $result = self::$db->createCommand($sql)->queryAll();
        if ($key && count($result) > 0) {
            $fiels = array();
            foreach ($result as $key => $value) {
                $fiels[$value['field_id']] = $value;
            }
            $result = $fiels;
        }
        return $result;
    }
    
    public function delFields($id = 0) {
        $id == 0 && $id = $this->primaryKey;
        //没有提交字段,删除之前所有字段
        FormField::model()->deleteAllByAttributes(array(
            'form_id' => $id
        ));
    }
}
?>