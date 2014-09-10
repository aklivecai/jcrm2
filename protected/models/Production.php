<?php
/**
 * 这个模块来自表 "{{production}}".
 *
 * 数据表的字段 '{{production}}':
 * @property string $itemid
 * @property string $name
 * @property string $fromid
 * @property string $manageid
 * @property string $add_time
 * @property string $add_us
 * @property string $add_ip
 * @property string $modified_time
 * @property string $modified_us
 * @property string $modified_ip
 * @property string $note
 */
class Production extends DbRecod {
    public static $table = '{{production}}';
    public $scondition = ' status>0 '; /*默认搜索条件*/
    public function rules() {
        return array(
            array(
                'name',
                'required'
            ) ,
            array(
                'itemid, manageid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'name,company',
                'length',
                'max' => 100
            ) ,
            array(
                'fromid, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'itemid, name, fromid, manageid, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note,status,company',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号', /*(来自成本,订单编号)*/
            'name' => '生产单名字',
            'company' => '客户',
            'fromid' => '平台会员ID',
            'manageid' => '会员ID',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
            'status' => '状态', /*(0:回收站,1:正常)*/
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('company', $this->company, true);
        $this->setCriteriaTime($criteria, array(
            'add_time',
            'modified_time'
        ));
        
        $info_product = Tak::getParam('info-product', false);
        if ($info_product) {
            $sql = "itemid IN (SELECT cp.cost_id FROM :CostProduct AS cp WHERE cp.fromid=:fromid AND cp.name LIKE ':info_product%' GROUP BY cp.cost_id )";
            $sql = strtr($sql, array(
                ':CostProduct' => CostProduct::$table,
                ':table' => self::$table,
                ':fromid' => Ak::getFormid() ,
                ':info_product' => $info_product,
            ));
            $criteria->addCondition($sql);
        }
        $criteria->compare('note', $this->note, true);
        return $cActive;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $condition = array(
            $arr['condition']
        );
        // $condition[] = 'display>0';
        $arr['order'] = "add_time DESC";
        $arr['condition'] = join(" AND ", $condition);
        return $arr;
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            if ($this->isNewRecord) {
            } else {
            }
        }
        return $result;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
    }
    public function getState() {
        return self::getStateText($this->status);
    }
    
    public static function getStateText($status) {
        switch ($status) {
            case 1:
                $result = '开始排期';
            break;
            case 2:
                $result = '排期完成';
            break;
            case 3:
                $result = '生产中';
            break;
            case 8:
                $result = '完成';
            break;
            default:
            break;
        }
        return $result;
    }
    /**
     * 生产已完成
     */
    public function StatusOver() {
        $this->upStatus(8);
    }
    public function isOver() {
        return $this->status == 8;
    }
    //修改生产状态
    public function upStatus($status) {
        $sql = strtr('UPDATE :table SET status=:status,modified_time=:time WHERE fromid = :fromid AND itemid=:itemid', array(
            ':table' => self::$table,
            ':status' => $status,
            ':fromid' => $this->fromid,
            ':itemid' => $this->itemid,
            ':time' => Ak::now() ,
        ));
        self::$db->createCommand($sql)->execute();
    }
    /**
     * 更新生产工序用时
     * @return [type] [description]
     */
    public function upPdays() {
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
    /**
     * 查询车间中的产品列表，关联核算中的产品信息
     * @param  int $itemid 生产编号
     * @return [type]         [description]
     */
    public function getProducts($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT p.*,pp.workshop_id FROM :cost_product AS p LEFT JOIN (SELECT product_id,workshop_id FROM :production_product WHERE fromid=:fromid AND production_id=:itemid  GROUP BY product_id ) AS pp ON p.itemid=pp.product_id WHERE p.fromid=:fromid AND  p.cost_id=:itemid ";
        $data = array(
            ':production_product' => ProductionProduct::$table,
            ':cost_product' => CostProduct::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        // Tak::KD($sql);
        $result = self::$db->createCommand($sql)->queryAll();
        return $result;
    }
    /**
     * 查询生产中的工序，用时
     * @param  integer $itemid [description]
     * @return [type]          [description]
     */
    public function getProcess($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT * FROM :production_days WHERE fromid=:fromid AND production_id=:itemid";
        $data = array(
            ':production_days' => ProductionDays::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        $result = self::$db->createCommand($sql)->queryAll();
        return $result;
    }
    
    public function getWid() {
    }
    
    public function getPidWid($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT product_id,workshop_id FROM :table WHERE fromid = :fromid AND production_id=:itemid ORDER BY product_id DESC";
        $data = array(
            ':table' => ProductionProduct::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        
        $sql = strtr($sql, $data);
        $tags = self::$db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($tags as $value) {
            $result[$value['product_id']] = $value['workshop_id'];
        }
        return $result;
    }
}
