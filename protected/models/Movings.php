<?php
class Movings extends DbRecod {
    public $linkName = array(
        'enterprise',
        'time'
    );
    public $type = null;
    private $_typename = '';
    private $product_movings = null;
    private $products = null;
    public $time = '';
    public static $table = '{{movings}}';
    public $tableAlias = false;
    public function init() {
        parent::init();
        $this->setSName();
        $this->time = time();
    }
    
    public function initak($type) {
        if ($type) {
            $this->type = $type;
            $this->setType($type);
            $this->setSName();
        }
    }
    
    public function setType($type) {
        $this->scondition.= " AND type = '$type' ";
    }
    
    public function setSName() {
        $this->_typename = Tak::getMovingsType($this->type);
        $this->sName = Tk::g($this->_typename);
    }
    
    public function getTypeName() {
        return $this->_typename;
    }
    
    public function getProductMovings($pageSize = 1000) {
        if ($this->product_movings === null) {
            $dataProvider = new CActiveDataProvider('ProductMoving', array(
                'criteria' => array(
                    'condition' => '  movings_id=' . $this->itemid,
                ) ,
                'pagination' => array(
                    'pageSize' => $pageSize,
                ) ,
            ));
            $this->product_movings = $dataProvider;
        }
        // Tak::KD($this->product_movings,1);
        return $this->product_movings;
    }
    
    public function rules() {
        return array(
            array(
                'typeid, time, enterprise, warehouse_id',
                'required'
            ) ,
            array(
                'type, status, warehouse_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, time, typeid, time_stocked, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'numbers, enterprise, us_launch',
                'length',
                'max' => 100
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'itemid, fromid, type, numbers, time, typeid, enterprise, us_launch, time_stocked, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note, status,warehouse_id',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'time',
                'checkTime'
            ) ,
        );
    }
    /**
     * @return array relational rules. 表的关系，外键信息
     */
    public function relations() {
        $condition = array(
            "item='" . strtolower($this->_typename) . "-type'"
        );
        $condition[] = 'fromid=0';
        return array(
            'iType' => array(
                self::BELONGS_TO,
                'TakType',
                'typeid',
                'select' => 'typename',
                'condition' => implode(" AND ", $condition)
            ) ,
        );
    }
    public function attributeLabels() {
        $stype = Yii::app()->getController()->id;
        if ($stype == 'purchase') {
            $stype = 'Purchase';
        } elseif ($stype == 'sell') {
            $stype = 'Sell';
        } else {
            $stype = $this->_typename;
        }
        $enterprise = Tk::g($stype . ' enterprise');
        $typeid = Tk::g($stype . ' typeid');
        $time = Tk::g($stype) . '日期';
        $numbers = Tk::g($stype . ' numbers');
        // Tak::KD($time);
        // Tak::KD(Yii::app()->getController()->id,1);
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'warehouse_id' => '仓库',
            'type' => '类型', /*(1:入库|2:出库)*/
            'numbers' => $numbers,
            'time' => $time,
            'typeid' => $typeid,
            'enterprise' => $enterprise, /*$enterprise单位名称'*/
            'us_launch' => '经手人',
            'time_stocked' => '确认操作日期',
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
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('type', $this->type);
        /*仓库范围查询*/
        if (is_array($this->warehouse_id)) {
            $criteria->addInCondition('warehouse_id', $this->warehouse_id);
        } elseif ($this->warehouse_id > 0) {
            $criteria->compare('warehouse_id', $this->warehouse_id);
        }
        if ($this->typeid >= 0) {
            $criteria->compare('typeid', $this->typeid);
        }
        $this->setCriteriaTime($criteria, array(
            'time',
            'add_time',
            'modified_time'
        ));
        
        $criteria->compare('numbers', $this->numbers, true);
        $criteria->compare('enterprise', $this->enterprise, true);
        $criteria->compare('us_launch', $this->us_launch, true);
        $criteria->compare('time_stocked', $this->time_stocked, true);
        $criteria->compare('add_us', $this->add_us);
        $criteria->compare('add_ip', $this->add_ip);
        $criteria->compare('modified_us', $this->modified_us);
        $criteria->compare('modified_ip', $this->modified_ip);
        $criteria->compare('status', $this->status);
        
        $criteria->compare('note', $this->note, true);
        
        $times = array();
        foreach ($_GET['time'] as $key => $value) {
            $times[$key] = $value;
        }
        if (isset($times['time']) && count($times['time']) >= 1) {
            $times = $times['time'];
            $t0 = isset($times[0]) ? $times[0] : false;
            $t1 = isset($times[1]) ? $times[1] : false;
            $start = $t0 ? Tak::getDayStart(strtotime($t0)) : 0;
            $end = $t1 ? Tak::getDayEnd(strtotime($t1)) : 0;
            if ($start < 0 || $start > $end) {
                $start = $start > 0 ? $start : $end;
                if ($start > 0) {
                    $end = TaK::getDayEnd($start);
                }
            }
            if ($start > 0 && $end > $start) {
                $criteria->addBetweenCondition('time', $start, $end);
            }
        }
        $info_product = Tak::getParam('info-product', false);
        if ($info_product) {
            $sql = "itemid IN (SELECT mp.movings_id FROM :table_mp AS mp LEFT JOIN :table_p AS p ON p.itemid=mp.product_id WHERE p.fromid=:fromid AND p.name LIKE '%:info_product%' GROUP BY mp.movings_id )";
            $sql = strtr($sql, array(
                ':table_p' => Product::$table,
                ':table_mp' => ProductMoving::$table,
                ':fromid' => Tak::getFormid() ,
                ':info_product' => $info_product,
            ));
            $criteria->addCondition($sql);
        }
        
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
        $sql = implode(" AND ", $condition);
        $t = explode("AND type = '1'", $sql);
        if (count($t) > 1 && strpos($t[1], 'type') !== false) {
            $sql = implode($t);
        }
        $sql = str_replace("type = '1' AND", "type", $sql);
        $arr['order'] = 'add_time DESC';
        $arr['condition'] = $sql;
        return $arr;
    }
    public function isAffirm() {
        return $this->time_stocked > 0;
    }
    /**
     * 倒置类型，方便删除产品后，调整库存信息
     */
    private function ReverseTeyp() {
        $this->type = $this->type == 1 ? 2 : 1;
    }
    /**
     * 更新出入库产品的真实库存,如果没有确认则不操作
     * @param  array $data 传入的产品数量,产品编号
     * @return [type]       [description]
     */
    private function updateStockProduct($data) {
        if ($this->isAffirm()) {
            $model = Stocks::model()->findByAttributes(array(
                'warehouse_id' => $this->warehouse_id,
                'product_id' => $data['itemid']
            ));
            $stocks = $data['numbers'];
            $this->type != 1&&$stocks*=-1;
            if ($model === null) {
                $model = new Stocks('create');
                $model->attributes = array(
                    'product_id'=>$data['itemid'],
                    'warehouse_id' => $this->warehouse_id,
                    'stocks'=>$stocks,
                );
                $model->save();
            } else {
                $model->stocks = $model->stocks+$stocks;
                $model->save();
            }
            return true;
            $arr = array(
                ':time' => Ak::now() ,
                ':itemid' => $data['itemid'],
                ':numbers' => $data['numbers'],
                ':warehouse_id' => $this->warehouse_id,
                ':operate' => $this->type == 1 ? '+' : '-',
                ':stocks' => Stocks::$table,
            );            
            $sql = "UPDATE :stocks AS s SET s.stocks=s.stocks:operate:numbers , s.modified_time = :time WHERE s.product_id = :itemid AND s.warehouse_id = :warehouse_id ";
            $sql = strtr($sql, $arr);
            // Tak::KD($sql);
            self::$db->createCommand($sql)->execute();
            // Tak::KD($sql,1);            
        }
    }
    /**
     * 删除出入库中的产品
     * @param  int $itemid 产品出入库的编号
     * @return [type]         [description]
     */
    public function delProduct($itemid) {
        $model = ProductMoving::model()->findByAttributes(array(
            'itemid' => $itemid,
            'movings_id' => $this->itemid,
        ));
        /*
        Tak::KD($model->attributes);
        Tak::KD($itemid, 1);
        */
        if ($model != null) {
            /*记录要删除的数据,数量,产品编号,后续增减*/
            $data = array(
                'itemid' => $model->product_id,
                'numbers' => $model->numbers,
            );
            /*判断是否删除成功*/
            if ($model->delete()) {
                /*
                 更新产品库存信息
                  之前是出库，就得增加，入库，则减少
                */
                $this->ReverseTeyp();
                $this->updateStockProduct($data);
                $this->ReverseTeyp();
            }
        }
        return true;
    }
    /**
     * 保存出入库产品，有就修改，没有就新增
     * @param  [type] $product [description]
     * @return [type]          [description]
     */
    public function saveProduct($product) {
        $model = null;
        $result = false;
        $old_numbers = false;
        $model = ProductMoving::model()->findByAttributes(array(
            'itemid' => $product['itemid'],
            'type' => $this->type,
            'movings_id' => $this->itemid
        ));
        
        if ($model == null) {
            $model = new ProductMoving();
            $model->attributes = $product;
            $model->itemid = Tak::fastUuid();
            $model->fromid = $this->fromid;
            $model->movings_id = $this->itemid;
            $model->time_stocked = $this->time_stocked;
            $model->type = $this->type;
            $model->warehouse_id = $this->warehouse_id;
            /*Tak::KD($model->attributes);*/
            if (Product::model()->findByPk($product['product_id']) == null) {
                $result = '请选择产品!';
            } elseif ($model->save()) {
                $this->_saveProductStock($model->itemid);
                $result = $model->itemid;
            } else {
                $result = $model->getErrors();
            }
        } else {
            //记录之前久的产品数量
            $old_numbers = $model->numbers;
            $model->fromid = $this->fromid;
            $model->movings_id = $this->itemid;
            $model->type = $this->type;
            $model->warehouse_id = $this->warehouse_id;
            
            $model->time_stocked = $this->time_stocked;
            
            $model->numbers = $product['numbers'];
            $model->note = $product['note'];
            $model->price = $product['price'];
            /*            Tak::KD($product);
             Tak::KD($model->attributes);*/
            if ($model->save()) {
                /*Tak::KD($model->attributes);*/
                $result = '';
            } else {
                $result = $model->getErrors();
            }
        }
        //操作成功，更新库存信息
        if ($result == '' || is_numeric($result)) {
            $data = array(
                'itemid' => $model->product_id,
                'numbers' => $model->numbers,
            );
            $this->updateStockProduct($data);
            if ($old_numbers) {
                //清空以前留下来的数量
                $this->ReverseTeyp();
                $data['numbers'] = $old_numbers;
                $this->updateStockProduct($data);
                $this->ReverseTeyp();
            }
        }
        return $result;
    }
    /**
     * 保存产品仓库信息，没有则新增一个该仓库下的产品
     * @param  [type] $product_id [description]
     * @return [type]             [description]
     */
    private function _saveProductStock($product_id) {
        $idata = array(
            'product_id' => $product_id,
            'warehouse_id' => $this->warehouse_id
        );
        $mstock = Stocks::model()->findByAttributes($idata);
        if ($mstock == null) {
            $mstock = new Stocks('create');
            $idata['stocks'] = 0;
            $mstock->attributes = $idata;
            if ($mstock->save()) {
                /*Tak::KD($mstock->attributes);*/
            } else {
                /*Tak::KD($mstock,getErrors(),1);*/
            }
        }
    }
    
    public function checkProducts($proucts) {
        $result = true;
        if (!$proucts || !is_array($proucts) || count($proucts) == 0) {
            $result = false;
            $this->addError('', "请填入入库产品明细");
        } else {
            $iserr = false;
            $mproducts = array();
            foreach ($proucts as $key => $value) {
                if (!is_numeric($key) || $key <= 0) {
                    $iserr = true;
                    break;
                }
            }
            $mproducts = Product::model()->findAllByPk(array_keys($proucts));
            if (!$iserr && count($mproducts) != count($proucts)) {
                $iserr = true;
            }
            
            if ($iserr) {
                $result = false;
                $this->addError('', "入库产品明细输入不正确");
                $_arr = array();
            } else {
                $this->products = $_POST['Product'];
            }
            foreach ($mproducts as $key => $value) {
                $_arr[$value->itemid] = array(
                    'name' => $value->name,
                    'numbers' => $proucts[$value->itemid]['numbers'],
                    'price' => $proucts[$value->itemid]['price'],
                    'note' => $proucts[$value->itemid]['note'],
                );
            }
            $this->products = $_arr;
            return $result;
        }
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
        }
        return $result;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
        if ($this->products != null) {
            // 删除所有产品出库入明细
            ProductMoving::model()->deleteAllByAttributes(array(
                'type' => $this->type,
                'movings_id' => $this->itemid
            ));
            
            $tags = $this->products;
            $m = new ProductMoving;
            $m->type = $this->type;
            $m->movings_id = $this->itemid;
            $m->fromid = $this->fromid;
            $m->warehouse_id = $this->warehouse_id;
            $itemid = $m->itemid = Ak::fastUuid();
            foreach ($tags as $key => $value) {
                $m->setIsNewRecord(true);
                $itemid = Ak::numAdd($itemid, 2);
                $m->itemid = $itemid;
                $m->product_id = $key;
                $m->numbers = $value['numbers'];
                $m->price = isset($value['price']) ? $value['price'] : '0.00';
                $m->note = isset($value['note']) ? $value['note'] : '';
                if ($m->save()) {
                    /*保存成功,查找是否存在库存中,没有就插入新的,主要区别为,判断仓库*/
                    $this->_saveProductStock($key);
                } else {
                    /*Tak::KD($m->getErrors(),1);*/
                }
            }
        }
    }
    
    public function affirm() {
        $connection = self::$db;
        $transaction = $connection->beginTransaction();
        try {
            $time = Ak::now();
            $itemid = $this->itemid;
            $arr = array(
                ':time' => $time,
                ':itemid' => $itemid,
                ':warehouse_id' => $this->warehouse_id,
                ':operate' => $this->type == 1 ? '+' : '-',
                ':movings' => self::$table,
                ':stocks' => Stocks::$table,
                ':product_moving' => ProductMoving::$table,
            );
            $sql = "UPDATE :movings SET time_stocked=:time WHERE itemid=:itemid";
            $sql = strtr($sql, $arr);
            $connection->createCommand($sql)->execute();
            $sql = "UPDATE :stocks AS s ,:product_moving AS pm SET s.stocks=stocks :operate pm.numbers , s.modified_time = :time WHERE s.product_id = pm.product_id AND  pm.movings_id=:itemid  AND s.warehouse_id = :warehouse_id AND pm.time_stocked=0";
            $sql = strtr($sql, $arr);
            $connection->createCommand($sql)->execute();
            /*Tak::KD($sql,1);*/
            $sql = "UPDATE :product_moving SET time_stocked=:time WHERE movings_id=:itemid AND time_stocked=0 ";
            $sql = strtr($sql, $arr);
            $connection->createCommand($sql)->execute();
            
            $sql = strtr($sql, $arr);
            $connection->createCommand($sql)->execute();
            $transaction->commit();
        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $transaction->rollBack();
        }
    }
    public function getLink($itemid = false, $action = 'view') {
        if (!$itemid) {
            $itemid = $this->primaryKey;
        }
        $link = Yii::app()->createUrl(strtolower($this->_typename) . '/' . $action, array(
            'id' => $itemid
        ));
        return $link;
    }
    public function getTotal() {
        $data = array(
            ':movings_id' => $this->itemid,
            ':tabl' => ProductMoving::$table,
        );
        $sql = 'SELECT SUM(numbers*price) FROM :tabl WHERE movings_id=:movings_id';
        $sql = strtr($sql, $data);
        $total = self::$db->createCommand($sql)->queryScalar();
        $total = sprintf("%01.2f", $total);
        return $total;
    }
    
    private $_products = null;
    public function getProducts() {
        if ($this->_products === null) {
            $sql = "SELECT mp.itemid,p.itemid as product_id,p.name,p.unit,p.material,p.color,p.spec,mp.price,mp.numbers,mp.note FROM :table_mp AS mp LEFT JOIN :table_p AS p ON p.itemid=mp.product_id  WHERE p.fromid=:fromid AND mp.movings_id=:movings_id";
            $sql = strtr($sql, array(
                ':table_p' => Product::$table,
                ':table_mp' => ProductMoving::$table,
                ':fromid' => Tak::getFormid() ,
                ':movings_id' => $this->itemid,
            ));
            $this->_products = self::$db->createCommand($sql)->queryAll();
        }
        return $this->_products;
    }
}
/*
SELECT mp.movings_id FROM :table_mp AS mp LEFT JOIN :table_p AS p ON p.itemid=mp.product_id WHERE p.fromid=:fromid AND p.name LIKE '%:info_product%' GROUP BY mp.movings_id
*/
