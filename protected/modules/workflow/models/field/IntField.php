<?php
/*
 * 解析int类型字段
*/
class IntField implements FieldParseInterface {
    private $_tmp;
    public function __construct($option = array()) {
        foreach ($option as $key => $value) {
            $this->$key = $value;
        }
        $this->_tmp = "<div><span>{name}</span><input type='{type}' name='{inname}' value='{default}'/>{desc}</div>";
    }
    public function setType() {
        $this->_tmp = preg_replace('/\{inname\}/', $this->inname, $this->_tmp);
        $this->_tmp = preg_replace('/\{name\}/', $this->field_name, $this->_tmp);
    }
    
    public function setName() {
        $this->_tmp = preg_replace('/\{type\}/', $this->field_type, $this->_tmp);
    }
    
    public function setDefault() {
        $this->_tmp = preg_replace('/\{default\}/', $this->field_default, $this->_tmp);
    }
    
    public function setValue() {
    }
    
    public function setDesc() {
        $this->_tmp = preg_replace('/\{desc\}/', $this->field_desc, $this->_tmp);
    }
    public function show() {
        $this->setType();
        $this->setDefault();
        $this->setName();
        $this->setValue();
        $this->setDesc();
        return $this->_tmp;
    }
}
?>