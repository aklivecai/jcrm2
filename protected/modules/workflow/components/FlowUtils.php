<?php
/**
 *流程操作类
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-10 18:23:45
 * @version $Id$
 */

class FlowUtils extends CComponent {
    private $sqlData = null;
    private $flowInfo = null;
    private $fowForm = null;
    private $flowRun = null;
    private $flowRunPrc = null;
    private $stepInfo = null;
    private $db = null;
    private $_now = null;
    private $errors = array();
    private $fields = null;
    private $separator = '$$';
    private $userid = 0;
    
    private $isnext = true;
    public function __construct() {
        $this->init();
    }
    public function init() {
        $this->_now = Ak::now();
        $this->db = Ak::db(true);
        $this->sqlData = array(
            ':FlowStep' => FlowStep::$table,
            ':StepCondition' => StepCondition::$table,
            ':FlowRunPrc' => FlowRunPrc::$table,
            ':FormValue' => FormValue::$table,
            ':run_id' => 0, /*默认是,新建成功后,需要用新的来替换,已经保存的数据*/
        );
        $this->userid = Ak::getManageid();
        /*$this->raiseEvent('onClicked', array());*/
    }
    public function setHand($type) {
        $this->isnext = $type != 0;
    }
    private function isNext() {
        return $this->isnext;
    }
    public static function l() {
        // Ak::L(func_get_args());
        // 1创建,2找流程,3跳转流程
        $ids = func_get_args();
        if ($ids[0] === 4 || $ids[0] == - 1) {
            $a = new Ak();
            call_user_func_array(array(
                $a,
                'L'
            ) , func_get_args());
        }
    }
    private function clear() {
        $sqls = array(
            'TRUNCATE oawork_form_value',
            'TRUNCATE OaWork_flow_run_prc',
            'TRUNCATE OaWork_flow_run',
            'UPDATE `Tak_Files` SET version_id=0  WHERE parent_file_id=50',
        );
        foreach ($sqls as $key => $value) {
            $this->db->createCommand($value)->query();
        }
    }
    
    private function loadModel($id, $m, $strError = false) {
        $model = $m::model()->findByPk($id);
        if ($model == null && $strError) {
            $this->onErrors($strError);
        }
        return $model;
    }
    /**
     * 根据流程编号初始化,流程对象,和流程的表单
     * @param  [type]  $flow_id [description]
     * @param  boolean $iserror 浏览的时候不或者在进行中的时候,哪怕流程已经不启用也可以用
     * @return [type]           [description]
     */
    private function initFlow($flow_id, $iserror = false) {
        $this->flowInfo = $this->loadModel($flow_id, 'FlowInfo', '不存在的流程');
        $reuslt = false;
        if ($this->flowInfo->status == 1 && $iserror) {
            $this->errors[] = '流程已经停用';
            $reuslt = false;
        } else {
            $this->sqlData[':flow_id'] = $this->flowInfo->primaryKey;
            //获取流程表单信息
            $this->fowForm = $this->loadModel($flow_id, 'FormInfo');
            $this->sqlData[':form_id'] = $this->fowForm->primaryKey;
            $reuslt = true;
        }
        return $reuslt;
    }
    /**
     * 获取流程的所有对象
     * @return [type] [description]
     */
    private function getMs() {
        return array(
            'flowInfo' => $this->flowInfo,
            'fowForm' => $this->fowForm,
            'model' => $this->flowRun,
            'stepInfo' => $this->stepInfo,
        );;
    }
    /**
     * 已经是过滤过的了,确认传入值是正确的,这里面的数据不进行二次校验
     * @param  [type] $run_id [description]
     * @return [type]         [description]
     */
    public function getViewFlow($run_id) {
        $flowRun = $this->flowRun = $this->loadModel($run_id, 'FlowRun', '不存在流程信息');
        $this->sqlData[':run_id'] = $flowRun->primaryKey;
        if ($this->initFlow($flowRun->flow_id)) {
            $this->stepInfo = $this->loadModel($flowRun->step_id, 'FlowStep', '不存在步骤信息');
            $this->sqlData[':step_id'] = $this->stepInfo->primaryKey;
            
            return $this->getMs();
        }
        if (count($this->errors) > 0) {
            $this->onErrors($this->errors);
        }
    }
    /**
     * 处理流程
     * @param [type] $type 类型[next,pre]
     * @param [type] $note 办理理由
     */
    public function HandleRun($type, $note, $data) {
        $this->flowRunPrc = FlowRunPrc::model()->findByAttributes(array(
            'run_id' => $this->sqlData[':run_id']
        ) , array(
            'order' => 'prc_id DESC'
        ));
        // Tak::KD($this->flowRunPrc->attributes);
        
        $this->setHand($type);
        $this->flowRunPrc->attributes = array(
            'status' => $this->isNext() ? 1 : 2,
            'remark' => $note,
            'handel_time' => $this->_now,
        );
        // self::l(4, $this->flowRunPrc->attributes);
        $this->createFlowData($data);
    }
    /**
     * 根据流程编号创建申请
     * @param  int $flow_id 流程编号
     * @return [type]          [description]
     */
    public function createFlow($flow_id) {
        if ($this->initFlow($flow_id, true)) {
            //获取第一个步骤
            $this->stepInfo = $this->flowInfo->getFirstStep();
            $this->sqlData[':step_id'] = $this->stepInfo->primaryKey;
            $this->flowRun = new FlowRun();
            return $this->getMs();
        }
        if (count($this->errors) > 0) {
            $this->onErrors($this->errors);
        }
    }
    /**
     * 创建审批
     * @param  array $data array('FlowRun'=>'申请内容','fields'=>'申请表值','files'=>'上传的附件')
     * @return [type]       [description]
     */
    public function createFlowData($data) {
        $errors = array();
        $model = $this->flowRun;
        $run_prc = $this->flowRunPrc;
        //第一次创建
        if ($this->stepInfo->isFirst()) {
            if ($model->isNewRecord) {
                //清空之前测试数据
                // $this->clear();
            }
            $model->attributes = isset($data['FlowRun']) && is_array($data['FlowRun']) ? $data['FlowRun'] : array();
            $model->flow_id = $this->flowInfo->primaryKey;
            $model->flow_name = $this->flowInfo->flow_name;
            $model->step_id = $this->stepInfo->primaryKey;
            if (!$model->attach) {
                $model->attach = '0';
            }
        }
        if ($model->validate()) {
            
            $_fields = isset($data['fields']) && is_array($data['fields']) ? $data['fields'] : array();
            //更新表单字段
            $fieldsIds = $this->setWFields($_fields);
            self::l('更新表单字段', $fieldsIds, $_fields);
            //没有错误,继续执行
            if (count($this->errors) == 0) {
                //更新附件信息
                $filesD = isset($data['files']) && is_array($data['files']) ? $data['files'] : array();
                $attach = $this->setFiles($filesD);
                self::l('附件信息', $attach, $filesD);
                if (is_array($attach)) {
                    $temps = explode(',', $model->attach);
                    $temps = array_unique(array_merge($temps, $attach));
                    $model->attach = implode(',', $temps);
                    self::l('更新附件信息', $attach, $model->attach);
                }
                //可以是创建的,也可以是退回到第一步的
                if ($this->stepInfo->isFirst() && $model->save()) {
                    self::l('保存流程', $model->attributes);
                    $itemid = $this->sqlData[':run_id'] = $model->primaryKey;
                    self::l(sprintf('itemid - %s', $itemid));
                    if (count($attach) > 0) {
                        // 更新附件中的编号
                        TakFile::model()->upVId($attach, $itemid, 50);
                    }
                    if (count($fieldsIds) > 0) {
                        // 更新表单中的编号
                        $sql = sprintf('UPDATE :FormValue SET run_id=:run_id  WHERE run_id=0 AND field_id IN(%s)', implode(',', $fieldsIds));
                        
                        $sql = strtr($sql, $this->sqlData);
                        $this->db->createCommand($sql)->query();
                    }
                    //建立流程第一步.
                    if ($run_prc == null) {
                        $run_prc = new FlowRunPrc('create');
                        $run_prc->attributes = array(
                            'run_id' => $itemid,
                            'step_user' => $model->user,
                            'step_name' => $this->stepInfo->step_name,
                            'step_no' => $this->stepInfo->step_no,
                            'step_id' => $this->stepInfo->primaryKey,
                            'start_time' => $model->start_time,
                            'handel_time' => $model->start_time,
                            'status' => 1,
                        );
                        self::l('$model->start_time', $model->start_time);
                        $this->setHand(true);
                        self::l('流程第一步', $run_prc->attributes);
                    }
                }
                
                if ($run_prc->save()) {
                    self::l('流程保存', $run_prc->attributes);
                    //出发流程转交动作,将流程转交到第二步
                    if ($this->transmit()) {
                        $this->onSuccess('成功申请!');
                    }
                }
            } else {
                self::l('步骤必填字段没有填写', $this->errors);
                //步骤必填字段没有填写
                $errors = $this->errors;
            }
        } else {
            $errors = $model->getErrors();
        }
        if (count($errors) > 0) {
            $info = Tak::getMsgByErrors($errors);
            $this->onErrors($info);
        }
    }
    /**
     * 更新提交过来的附件,字段列表
     * @param array $files array(1,2,3)
     */
    private function setFiles($files) {
        if ($files && is_array($files)) {
            $filesIds = array();
            $itemid = $this->sqlData[':run_id'];
            foreach ($files as $value) {
                $__id = Tak::getSId($value);
                if ($__id > 0) {
                    $filesIds[] = $__id;
                }
            }
            //查找附件列表,且是没有使用过的
            $ids = TakFile::model()->upVId($filesIds, $itemid, 50);
            return $ids;
        }
    }
    /**
     * 更新提交过来的表单字段,还得过滤,只能是当前步骤可以输入的字段
     * @param  array $data 提交过来的数据[array('idxx'=>123)]
     * @return [type]     返回新增的字段编号  array(1,2,3)
     */
    private function setWFields($data) {
        //当前步骤需要可以输入的字段
        $files_attr = $this->stepInfo->getWFields();
        self::l('当前步骤需要可以输入的字段', $files_attr);
        $result = array();
        if (count($files_attr) > 0) {
            //需要统计那些要的必填的字段,一定是要数组
            if (!$data || !is_array($data)) {
                $data = array();
            }
            $errors = array();
            $itemid = $this->sqlData[':run_id'];
            $ids = array_keys($files_attr);
            $idsStr = sprintf('%s%s%s', $this->separator, implode($this->separator, $ids) , $this->separator);
            $_fields = $data;
            $writeFiles = array();
            $newVal = array(); //需要插入的字段和值
            self::l('$_fields', $_fields);
            self::l('$idsStr', $idsStr);
            //获取需要填写POST过来的字段数据
            foreach ($_fields as $key => $value) {
                //解码id
                $__id = Tak::getSId($key);
                if (strpos($idsStr, $__id . $this->separator)) {
                    $writeFiles[$__id] = $value;
                }
            }
            self::l('writeFiles', $writeFiles);
            // Tak::KD($writeFiles);
            //需要录入的字段对象
            $fieldsObj = $this->fowForm->getFields($ids);
            foreach ($fieldsObj as $value) {
                $__id = $value->primaryKey;
                $val = null;
                if (isset($writeFiles[$__id]) && $writeFiles[$__id] != '') {
                    //后期需要进行值的过滤,如select,radio,checkbox 只能选择已有的内容
                    $val = $writeFiles[$__id];
                }
                if ($val !== null) {
                    if ($value->otype == 'checkbox') {
                        $val = implode($this->separator, $val);
                    }
                    $newVal[$__id] = $val;
                } elseif ($files_attr[$__id]['must'] == 1) {
                    // 必填选项
                    $errors[] = sprintf('[%s] 必需填写不能为空', $value->field_name);
                }
            }
            if (count($errors) > 0) {
                $this->errors = $errors;
            } else {
                self::l('newVal', $newVal);
                //非新建流程,可以找到值来更新,并移除
                if ($itemid > 0) {
                    $sql = sprintf('run_id=%s AND field_id IN(%s)', $itemid, implode(',', array_keys($newVal)));
                    $list = FormValue::model()->findAll($sql);
                    foreach ($list as $key => $value) {
                        $__id = $value->field_id;
                        
                        $value->value = $newVal[$__id];
                        $value->save(); //可能会有异常
                        unset($newVal[$__id]);
                    }
                }
                //如果是新建,后面新建需要,更新run_id
                if (count($newVal)) {
                    $fVal = new FormValue('create');
                    $fVal->run_id = $itemid;
                    $fVal->form_id = $this->sqlData[':form_id']; /*插入表单吧编号.方便后期删除表单的时候统一删除*/
                    foreach ($newVal as $key => $value) {
                        if (!$fVal->isNewRecord) {
                            $fVal->setIsNewRecord(true);
                            $fVal->id+= 1;
                        }
                        $fVal->field_id = $key;
                        $fVal->value = $value;
                        $fVal->save();
                    }
                    $result = array_keys($newVal);
                }
            }
        }
        return $result;
    }
    public function addEvent() {
        $this->raiseEvent('onErrors', array());
    }
    public function onErrors($event) {
        $this->raiseEvent('onErrors', $event);
    }
    public function onSuccess($event) {
        $this->raiseEvent('onSuccess', $event);
    }
    /**
     * 找出当前步骤有 下一个步骤的区间
     * (可能有条件),
     * @return int 返回下步骤的编号,没有返回null
     */
    public function getSectionSteps() {
        $_sql = 'SELECT step_id,step_no,conditions FROM :FlowStep WHERE flow_id=:flow_id AND step_id>:step_id ';
        $nextid = null;
        //找出最后一位的没有条件的步骤
        $sql = sprintf(' %s AND conditions=0 LIMIT 1', $_sql);
        $sql = strtr($sql, $this->sqlData);
        
        $nextObj = $this->db->createCommand($sql)->queryRow();
        if (count($nextObj) > 0) {
            //连在一起的子节点,不用找了直接返回
            if ($nextObj['step_no'] - $this->stepInfo->step_no == 1) {
                $nextid = $nextObj['step_id'];
            } else {
                $sqlWhere.= ' AND step_id<' . $nextObj['step_id'];
                //找出区间内有条件的步骤
                $sql = sprintf(' %s AND conditions>0 ORDER BY step_no ASC', $_sql);
                $sql = strtr($sql, $this->sqlData);
                $ids = $this->db->createCommand($sql)->queryColumn();
                self::l(2, '找出区间内有条件的步骤', $sql, $ids);
                if (count($ids) > 0) {
                    $nextid = $this->getStepByIds($ids);
                }
            }
            //条件都不成立只能用条件后面的步骤
            if ($nextid === null) {
                $nextid = $nextObj['step_id'];
            }
        }
        self::l(2, '找出最后一位的没有条件的步骤,sql,nextObj,nextid', $sql, $nextObj, $nextid);
        
        return $nextid;
    }
    /**
     * [getSectionStesp description]
     * @return [type] [description]
     */
    public function getnNexStesp() {
        $id = $this->getSectionSteps();
        $step = null;
        if ($id != null) {
            $sql = sprintf('flow_id=:flow_id AND step_id=%s', $id);
            $sql = strtr($sql, $this->sqlData);
            $step = FlowStep::model()->find($sql);
        }
        return $step;
    }
    /**
     * 获取当前进度的上一个步骤
     * @return array() [description]
     */
    public function getLastRun() {
        $sql = 'SELECT step_id FROM :FlowRunPrc WHERE run_id = :run_id AND  step_id<:step_id ORDER BY step_no DESC LIMIT 1';
        // $sql = ' run_id = :run_id AND  step_id<:step_id ';
        $sql = strtr($sql, $this->sqlData);
        $step_id = $this->db->createCommand($sql)->queryScalar();
        $step = null;
        if ($step_id && $step_id > 0) {
            $sql = sprintf('flow_id=:flow_id AND step_id=%s', $step_id);
            $sql = strtr($sql, $this->sqlData);
            $step = FlowStep::model()->find($sql);
        }
        return $step;
    }
    /**
     * 转交流程
     * @param  string $type ['next','up']
     * @return [type]       [description]
     */
    public function transmit() {
        if ($this->isNext()) {
            self::l(3, '提交下一步');
            $step = $this->getnNexStesp();
            if ($step == null) {
                /*流程没有下一步骤.结束流程*/
                return $this->finish();
            } else {
            }
        } else {
            self::l(3, '退回上一步');
            $step = $this->getLastRun();
        }
        if ($step != null) {
            //开始新的步骤流程
            $new_prc = new FlowRunPrc('carete');
            if ($step->timeout > 0) {
                $nowDate = date("Y") . '-' . date("m") . '-' . date("d");
                $timeout = $step->timeout;
                $timeout = strtotime("$nowDate +$timeout day");
            } else {
                $timeout = 0;
            }
            $new_prc->attributes = array(
                'run_id' => $this->flowRun->primaryKey,
                'step_user' => $step->isFirst() ? $this->flowRun->user : $step->step_user, //退回到申请人手里,后期可以授权委托,审批,当前只能读取流程中的人来设置
                'step_name' => $step->step_name,
                'step_no' => $step->step_no,
                'step_id' => $step->primaryKey,
                'start_time' => $this->_now,
                'handel_time' => 0,
                'timeout' => $timeout,
            );
            
            if ($new_prc->save()) {
                self::l(3, '开始新的步骤流程', $new_prc->attributes);
                //更新进度,的时间和步骤编号
                $flowRun = $this->flowRun;
                $flowRun->modified_time = $new_prc->start_time;
                //当前流程到达步骤ID
                $flowRun->step_id = $new_prc->step_id;
                
                $flowRun->step_name = $new_prc->step_name;
                $flowRun->cuser_id = $new_prc->step_user;
                
                $flowRun->cuser_name = $flowRun->getUsername($new_prc->step_user);
                //更新最后修改时间
                if (!$flowRun->isNewRecord) {
                    $flowRun->modified_time = $this->_now;
                }
                if ($flowRun->save()) {
                    self::l(3, '更新进度,的时间和步骤编号', $flowRun->attributes);
                    return true;
                } else {
                    self::l(-1, $flowRun->getErrors());
                    return false;
                }
            } else {
                // self::l(-1, $new_prc->attributes);
                return false;
            }
        }
        return false;
    }
    /**
     *完成,更新流程完成时间,和进度
     * @return [type] [description]
     */
    public function finish() {
        $this->flowRun->run_state = 1;
        $this->flowRun->end_time = $this->_now;
        $this->flowRun->save();
        return true;
    }
    /**
     * 最近一个符合条件的步骤
     * @param  array $ids [description]
     * @return [type]      [description]
     */
    private function getStepByIds($ids) {
        $id = null;
        $fields = $this->getFieldVal();
        $conditions = $this->getStepConditionsByStepid($ids);
        $con = new TakCondition();
        foreach ($conditions as $step_id => $val) {
            //当前步骤的条件数量
            $_coutns = count($val);
            //记录步骤条件成立的数量
            $_i = 0;
            foreach ($val as $value) {
                $v = isset($fields[$value['field_id']]) ? $fields[$value['field_id']] : '';
                if (!$con->decide($value['value'], $v, $value['type'])) {
                    break;
                } else {
                    $_i++;
                }
            }
            if ($_coutns == $_i) {
                $id = $step_id;
                break;
            }
        }
        self::l(2, '最近一个符合条件的步骤', $fields, $conditions, $id);
        return $id;
    }
    /**
     * 查询步骤的所有条件,分组
     * @param  array    $ids 步骤编号数组
     * @return  array      已经根据步骤编号分好组的条件
     */
    private function getStepConditionsByStepid($ids) {
        $sql = 'SELECT step_id,field_id,type,value FROM  :StepCondition WHERE step_id IN(:ids) ORDER BY step_id ASC ';
        $data = array(
            ':ids' => implode(',', $ids)
        );
        $data+= $this->sqlData;
        $sql = strtr($sql, $data);
        $tags = $this->db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($tags as $key => $value) {
            $__id = $value['step_id'];
            !isset($result[$__id]) && $result[$__id] = array();
            $result[$__id][] = $value;
        }
        self::l(2, '查询步骤的所有条件,分组 : sql,tags,reuslt', $sql, $tags, $result);
        return $result;
    }
    /**
     * 获取表单中的值
     * @return array
     */
    private function getFieldVal() {
        $sql = 'SELECT field_id,value FROM :FormValue WHERE run_id=:run_id;';
        $sql = strtr($sql, $this->sqlData);
        $tags = $this->db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($tags as $key => $value) {
            $result[$value['field_id']] = $value['value'];
        }
        self::l(2, '获取表单中的值', $sql, $result);
        return $result;
    }
}
