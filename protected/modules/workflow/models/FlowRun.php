<?php
/**
 * 申请流程具体申请
 * 正在进行的工作流信息（开始时间,结束时间,状态，发起人等）
 */
class FlowRun extends DbiRecod {
    public static $table = 'oawork_flow_run';
    public $end_time = 0;
    public $run_state = 0;
    public $attach = '0';
    
    public $cuser_id = 0;
    public $cuser_name = '';
    
    private $status = '';
    private $userName = '';
    
    public $suid = 0;
    
    public $title = "生病了,需要请假三天";
    public $describe = "需要请假三天";
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function primaryKey() {
        return 'run_id';
    }
    
    public function rules() {
        return array(
            array(
                'title,describe,flow_id,step_id',
                'required'
            ) ,
            array(
                'run_state, step_id, end_time, start_time',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'fromid, time, add_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'flow_name, describe,cuser_name,step_name',
                'length',
                'max' => 255
            ) ,
            array(
                'user,cuser_id',
                'length',
                'max' => 25
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'flow_name, describe, user, time, run_state, step_id, end_time, start_time, add_ip',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'run_prc' => array(
                self::HAS_MANY,
                'FlowRunPrc',
                'run_id',
                'order' => 'run_prc.prc_id desc'
            ) ,
            'flow_info' => array(
                self::BELONGS_TO,
                'FlowInfo',
                'flow_id'
            ) ,
            'prc_data' => array(
                self::HAS_ONE,
                'FormModel',
                'run_id'
            ) ,
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $arr['order'] = 'modified_time DESC ';
        return $arr;
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        $arr = Ak::getOM();
        if ($this->isNewRecord) {
            $this->fromid = $arr['fromid'];
            $this->user = $arr['manageid'];
            $this->start_time = $arr['time'];
            $this->add_ip = $arr['ip'];
        } else {
        }
        return $result;
    }
    /**
     * @return array customized attribute labels (name=&gt;label)
     */
    public function attributeLabels() {
        return array(
            'run_id' => '进程编号',
            'fromid' => '企业编号',
            
            'flow_id' => '流程编号',
            'flow_name' => '流程', /*(用户自定义+流程名字)*/
            
            'step_id' => '进程当前步编号',
            'step_name' => '当前步骤',
            'cuser_id' => '进程步骤当前办理人编号', //(方便在已办理流程中查看)
            'cuser_name' => '当前步骤处理人', //进程步骤当前办理人名字
            
            'title' => '标题', /*(用户自定义+流程名字)*/
            
            'describe' => '申请理由',
            'user' => '申请人',
            'time' => '处理期限',
            'run_state' => '流程状态(0未结束,1结束)',
            
            'attach' => '附件id列表',
            'end_time' => '结束时间',
            'start_time' => '申请时间', //流程开始时间
            'add_ip' => '添加IP',
            'modified_time' => '流程更新时间', //(为空的时候,申请人可以退回,申请人下一步操作过后更新)
            
            'userName' => '申请人',
            'status' => '流程状态',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('flow_id', $this->flow_id);
        $criteria->compare('run_state', $this->run_state);
        if ($this->user > 0) {
            $criteria->compare('user', $this->user);
        }
        if ($this->cuser_id > 0) {
            $criteria->compare('cuser_id', $this->cuser_id);
        }
        return $cActive;
    }
    /**
     * 默认查询申请人名字,可以拱给其步骤或者查询名字,为了后期统一,可能后期修改条件什么的
     * @return [type] [description]
     */
    public function getUsername() {
        if (func_num_args() == 1) {
            $args = func_get_args();
            $uid = $args[0];
        } else {
            $uid = $this->user;
        }
        return Manage::getNameById($uid);
    }
    public function getStatus() {
        return $this->end_time > 0 ? '结束' : '办理中';
    }
    /**
     * 获取流程的表单值
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getFieldValues($id = 0) {
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
    /**
     * 获取附件列表
     * @return [type] [description]
     */
    public function getFiles() {
        $ids = explode(',', $this->attach);
        $result = array();
        if (count($ids) > 0) {
            $list = TakFile::model()->setGetCU()->findAllByPk($ids);
            foreach ($list as $key => $value) {
                $result[] = $value->getInfo();
            }
        }
        return $result;
    }
    
    public function getRunPics() {
        $result = FlowRunPrc::model()->findAllByAttributes(array(
            'run_id' => $this->primaryKey
        ));
        return $result;
    }
    /**
     * 已经办理的流程,不会找出自己申请,后面退回自己办理的流程
     * @return [type] [description]
     */
    public function searchByUid() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $sql = "run_id IN (SELECT run_id FROM :table WHERE fromid=:fromid AND step_no>1 AND handel_time>0 AND step_user=:step_use GROUP BY run_id ORDER BY run_id DESC)";
        $sql = strtr($sql, array(
            ':table' => FlowRunPrc::$table,
            ':fromid' => Ak::getFormid() ,
            ':step_use' => $this->suid,
        ));
        $criteria->addCondition($sql);
        $criteria = $cActive->criteria;
        return $cActive;
    }
    public function searchBy($username) {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $sql = sprintf("t.run_id IN(SELECT run_id FROM %s WHERE step_no>0 AND step_user='%s' AND handel_time>0 GROUP BY run_id )", 'oawork_flow_run_prc', $username);
        $criteria->addCondition($sql);
        $criteria->with = array(
            'prc_data',
            'run_prc'
        );
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
