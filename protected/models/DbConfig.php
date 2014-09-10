<?php
class DbConfig extends CFormModel {
    public $dns;
    public $dbname;
    public $username;
    public $password;
    public $port = 3306;
    public function rules() {
        return array(
            array(
                'dns, dbname, username, password',
                'required',
            ) ,
            array(
                'dns, dbname, username, password',
                'length',
                'max' => 60
            ) ,
            array(
                'port',
                'numerical',
                'integerOnly' => true
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'dns' => '服务器地址',
            'dbname' => '数据库',
            'username' => '登录名',
            'password' => '密码',
            'port' => '端口',
        );
    }
    public function toString() {
        return self::getDbinfo($this->attributes);
    }
    
    public static function getDbinfo($data) {
        $result = array(
            'dns' => sprintf("mysql:dbname=%s;host=%s;port=%s", $data['dbname'], $data['dns'], $data['port']) ,
            'username' => $data['username'],
            'password' => $data['password'],
        );
        return $result;
    }
}
