<?php
/**
 * 用于正在进行的工作流信息（开始时间,结束时间,状态，发起人等）
 */
class FlowRunPrc extends DbiRecod {
    public $handel_time = 0;
    public $timeout = 0;
    public $start_time = 0;
    public $status = 0;
    
    private $userName = '';
    
    private $statuName = '';
    
    public static $table = 'oawork_flow_run_prc';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function rules() {
        return array(
            array(
                "run_id, step_name, step_user, step_no,step_id,start_time,status,timeout",
                "required"
            ) ,
            array(
                'step_id,status,handel_time,start_time,timeout',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'remark',
                'length',
                'max' => 255
            ) ,
            array(
                'run_id,step_user,handel_time',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function relations() {
        return array(
            'flow_run' => array(
                self::BELONGS_TO,
                'FlowRun',
                'run_id'
            ) ,
        );
    }
    
    public function getRunTime($key = 0) {
        $result = '';
        if ($key > 0 || $this->step_no > 1) {
            $result = Tak::timediff($this->start_time, $this->handel_time);
        }
        return $result;
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        $arr = Ak::getOM();
        if ($this->isNewRecord) {
            $this->fromid = $arr['fromid'];
        } else {
            //处理步骤人的ip地址
            $this->add_ip = $arr['ip'];
        }
        return $result;
    }
    public function getStatuName() {
        $colro = "";
        $text = '';
        //color_red
        switch ($this->status) {
            case 0:
                $colro = 'color_red';
                $text = '办理中';
            break;
            case 1:
                $colro = 'color_green';
                $text = '已办理';
            break;
            case 2:
                $colro = 'color_orange';
                $text = '退回';
            break;
            default:
            break;
        }
        return sprintf('<span class="%s">%s</span>', $colro, $text);
    }
    public function attributeLabels() {
        return array(
            'prc_id' => '进程步骤id',
            'run_id' => '进程id',
            'step_no' => '步骤号',
            'step_id' => '步骤id',
            'step_name' => '步骤名称',
            'step_user' => '步骤处理人',
            'start_time' => '步骤开始时间',
            'handel_time' => '步骤处理时间',
            'remark' => '处理步骤反馈信息',
            'timeout' => '步骤超时时间',
            'status' => '状态', /*(0;办理中,1已办理,2退回)*/
        );
    }
    
    public function search($user) {
        $criteria = new CDbCriteria;
        $criteria->addCondition('handel_time>0');
        $criteria->addCondition('step_no > 0');
        $criteria->addCondition('step_user = :user');
        $criteria->params[':user'] = $user;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /**
     * 获取用户当前需要处理的流程数量
     * @param  int $id 用户编号
     * @return [type]     [description]
     */
    public function getUcount($id) {
        //AND step_no>1 //退回情况不生效
        $sql = "SELECT COUNT(1) FROM :table WHERE fromid=:fromid  AND handel_time=0 AND step_user=:step_use ";
        $data = array(
            ':table' => FlowRunPrc::$table,
            ':fromid' => Ak::getFormid() ,
            ':step_use' => $id,
        );
        $sql = strtr($sql, $data);
        // return self::$db->createCommand($sql)->queryScalar();
        return FlowRunPrc::model()->count('handel_time=0 AND step_user=:step_use', array(
            ':step_use' => $id
        ));
    }
}
