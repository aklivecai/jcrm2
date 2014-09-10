<?php
class TRecord extends CActiveRecord {
    public static $table = null;
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            $this->fromid = Tak::getFormid();
            if ($this->isNewRecord) {
            } else {
            }
        }
        return $result;
    }
    /**
     * 检验重复
     */
    public function checkRepetition($attribute, $params) {
        $sql = array(
            "LOWER(:col)=:val"
        );
        $arr = array(
            ':col' => $attribute,
        );
        if ($this->primaryKey > 0) {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = $this->primaryKey();
            $arr[':itemid'] = $this->primaryKey;
            $sql[] = 'fromid=' . $this->fromid;
        } else {
            $sql[] = 'fromid=' . Tak::getFormid();
        }
        $sql = implode(' AND ', $sql);
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        // Tak::KD($m,1);
        if ($m != null) {
            $err = $this->getAttributeLabel($attribute) . ' 已经存在 :';
            $err.= $m->getHtmlLink();
            $this->addError($attribute, $err);
        }
    }
}
