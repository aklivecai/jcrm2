<?php
class FormModel extends DbiRecod {
    public $flow_name='xxx';
    public static $table = 'oawork_flow_form_1';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'id';
    }
    public function rules() {
        return array(
            array(
                'flow_name,describe',
                'required'
            ) ,
            array(
                'flow_name, describe',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, fromid, run_id, flow_name, describe, user, add_time, add_ip, time',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function relations() {
        return array(
            'flow_run' => array(
                self::HAS_ONE,
                'FlowRun',
                'run_id'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'id' => '主键',
            'fromid' => '企业编号',
            'run_id' => '流程编号',
            'flow_name' => '流程标题', /*(用户自定义+流程名字)*/
            'describe' => '申请理由',
            'user' => '申请人',
            'add_time' => '添加时间',
            'add_ip' => '添加IP',
            'time' => '处理期限',
        );
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        $arr = Ak::getOM();
        if ($this->isNewRecord) {
            $this->fromid = $arr['fromid'];
            $this->user = $arr['manageid'];
            $this->add_time = $arr['time'];
            $this->add_ip = $arr['ip'];
        } else {
        }
        return $result;
    }
    
    public function getFieldValues($id) {
        $id == 0 && $id = $this->primaryKey;
        $data = array(
            ':table' => FormValue::$table,
            ':run_id' => $id,
        );
        $sql = "SELECT field_id,value FROM :table  WHERE run_id=:run_id ";
        $sql = strtr($sql, $data);
        $result = self::$db->createCommand($sql)->queryAll();
        if (count($result) > 0) {
            $fiels = array();
            foreach ($result as $key => $value) {
                $fiels[$value['field_id']] = $value['value'];
            }
            $result = $fiels;
        }
        return $result;
    }
}
?>