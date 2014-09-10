<?php
/**
 * 字段解析的接口
 */
interface FieldParseInterface {
    public function setType();
    public function setName();
    public function setDefault();
    public function setValue();
    public function show();
}
?>