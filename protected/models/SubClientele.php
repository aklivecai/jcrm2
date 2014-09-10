<?php
class SubClientele extends Clientele {
    
    public $isLog = false; /*是否记录日志*/
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $sql = Subordinate::getSubManageSql();
        // Tak::KD($sql);
        $condition = array(
            'status=1',
            $sql
        );
        if (false && isset($arr['condition'])) {
            $condition[] = $arr['condition'];
        }
        //2014-08-15
        //防止查询条件为或的清空，查询下属全部状态的客户
        $condition[] = 'status=1';
        // Tak::KD(implode(" AND ", $condition) , 1);
        $arr['condition'] = implode(" AND ", $condition);
        
        return $arr;
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
