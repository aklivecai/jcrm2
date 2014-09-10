<?php
class OrderFlow extends MRecord {
    public $action_user = '系统';
    public static $table = '{{order_flow}}';
    public function init() {
        parent::init();
        $itemid = Tak::getState('order_flow_id');
        if (!$itemid) {
            $itemid = Tak::fastUuid();
            Tak::setState('order_flow_id', $itemid);
        }
        $this->itemid = $itemid;
    }
    
    public function scopes() {
        return array(
            'sort_time' => array(
                'order' => 'add_time DESC',
            )
        );
    }
    public function rules() {
        return array(
            array(
                'order_id',
                'required'
            ) ,
            array(
                'itemid, order_id, add_us',
                'length',
                'max' => 25
            ) ,
            array(
                'status, add_time, add_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'action_user, name',
                'length',
                'max' => 100
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'itemid, order_id, status, action_user, add_time, add_us, add_ip, note',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    
    public function relations() {
        return array();
    }
    
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'order_id' => '订单编号',
            'name' => '流程名字',
            'status' => '订单状态', /*(0:删除,1:    您提交了订单-请等待系统确认-客户,101等待付款-通过审核,201取消订单-无效订单,102等待发货-已经付款,103等待收货,.客户自己定义.,999完成订单,10订单变更)*/
            'action_user' => '操作人',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'note' => '备注详情',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid, true);
        $criteria->compare('order_id', $this->order_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('action_user', $this->action_user, true);
        $criteria->compare('add_time', $this->add_time, true);
        $criteria->compare('add_us', $this->add_us, true);
        $criteria->compare('add_ip', $this->add_ip, true);
        $criteria->compare('note', $this->note, true);
        return $cActive;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $condition = array();
        if (isset($arr['condition'])) {
            $condition[] = $arr['condition'];
        }
        $arr['condition'] = implode(" AND ", $condition);
        // $condition[] = 'display>0';
        $arr['order'] = ' add_time ASC ';
        return $arr;
    }
    protected function afterFind() {
        parent::afterFind();
        /*对应下单用户显示提示*/
        if ($this->status == 1 && $this->add_us == Tak::getManageid()) {
            $this->note = '您提交了订单-请等待系统确认';
        }
    }
    /*订单变更*/
    public function changeFlow() {
        $this->status = 10;
        $this->action_user = '客户';
        return $this->save();
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave(true);
        if ($result) {
            $arr = Tak::getOM();
            //添加数据时候
            if ($this->isNewRecord) {
                if (!$this->itemid) {
                    $this->itemid = $arr['itemid'];
                }
                $this->add_us = $arr['manageid'];
                $this->add_time = $arr['time'];
                $this->add_ip = $arr['ip'];
            } else {
            }
        }
        return $result;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
        Tak::setState('order_flow_id', false);
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
    
    public $files = null;
    public function loadFiles() {
        if ($this->files == null) {
            $this->files = OrderFiles::model()->getListByActionID($this->itemid);
        }
        return $this->files;
    }
    
    public function getFilesImg() {
        $result = '';
        $files = $this->loadFiles();
        foreach ($files as $k1 => $v1) {
            $result.= $v1->getLink();
        }
        return $result;
    }
    
    public function getName($fromid = 0) {
        $result = OrderType::item('order-status', $this->status);
        if (!$result) {
            if ($this->name) {
                $result = $this->name;
            } else {
                $result = TakType::item('order-flow', $this->status,$fromid);
            }
        }
        return $result;
    }
    /*查看订单最后流程*/
    public static function getOneLast($id) {
        $sql = sprintf('SELECT * FROM %s WHERE order_id = :order_id ORDER BY add_time DESC', self::$table);
        $arr = array(
            ':order_id' => $id
        );
        $command = Ak::getDb('db')->createCommand($sql);
        $model = $command->queryRow(true, $arr);
        return $model;
    }
    public function getListByOrder($condition) {        
        $arr = array();
        foreach ($condition as $key => $value) {
            $arr[$key] = $value;
        }
        // Ak::KD($arr,1);
        $list = $this->findAllByAttributes($arr);
        foreach ($list as $key => $value) {
            $value->loadFiles();
        }
        return $list;
    }
}
