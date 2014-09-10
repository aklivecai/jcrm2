<?php
/*
 * 用于接下下拉列表字段
*/
class SelectField implements FieldParseInterface {
    private $_tmp;
    private $_labeltmp;
    public function __construct($option = array()) {
        foreach ($option as $key => $value) {
            $this->$key = $value;
        }
        $this->_tmp = "<div><span>{name}</span><select name='{inname}'>{option}</select>{desc}</div>";
        $this->_labeltmp = '<div class="inline"><span>{name}</span><span>{value}</span>{desc}</div>';
    }
    public function setName() {
        $this->_tmp = preg_replace('/\{name\}/', $this->field_name, $this->_tmp);
    }
    
    public function setType() {
        $this->_tmp = preg_replace('/\{inname\}/', $this->inname, $this->_tmp);
    }
    
    public function setDefault() {
        $values = explode('\n', $this->field_value);
        $values = array_flip($values);
        if (array_key_exists($this->field_default, $values)) $default_index = $values[$this->field_default];
        else $default_index = 0;
        return $default_index;
    }
    
    public function setValue() {
        $default_index = $this->setDefault();
        $option = '';
        $values = explode("\n", $this->field_value);
        foreach ($values as $key => $text) {
            if ($default_index == $key) {
                $option.= '<option value="' . $key . '" selected="selected" >' . $text . '</option>';
            } else {
                $option.= '<option value=' . $key . '>' . $text . '</option>';
            }
        }
        $this->_tmp = preg_replace('/\{option\}/', $option, $this->_tmp);
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
    
    public function showlabel() {
        $this->setName();
        $this->setValue();
        $this->setDesc();
        return $this->_labeltmp;
    }
}
?>