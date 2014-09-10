<?php
class Cost extends DbRecod {
    public $linkName = 'name'; /*连接的显示的字段名字*/
    public static $table = '{{cost}}';
    public $scondition = 'status>0';
    public function rules() {
        return array(
            array(
                'name',
                'required'
            ) ,
            array(
                'status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'name',
                'length',
                'max' => 100
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'totals',
                'numerical',
            ) ,
            array(
                'name, totals, add_time, note, status',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号(来自订单编号)',
            'fromid' => '平台会员ID',
            'name' => '核算标题',
            'totals' => '总成本',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
            'status' => '状态(0:回收站,1:正常)',
        );
    }
    public function search() {
        
        $criteria = new CDbCriteria;
        
        $criteria->compare('name', $this->name, true);
        
        if ($this->totals > 0) {
            $totals = floatval($this->totals);
            $v = $_GET['comparison'] ? $_GET['comparison'] : false;
            $comparison = TakType::items('comparison');
            if (!isset($comparison[$v])) {
                $v = '';
            }
            switch ($v) {
                case 'then':
                    $criteria->compare('totals', $this->totals, true);
                break;
                case 'greater':
                    $criteria->addCondition("totals>$totals");
                break;
                case 'less':
                    $criteria->addCondition("totals<$totals");
                break;
                default:
                    $criteria->compare('totals', $totals);
                break;
            }
        }
        
        $info_product = Tak::getParam('info-product', false);
        if ($info_product) {
            $sql = "itemid IN (SELECT cp.cost_id FROM :CostProduct AS cp WHERE cp.fromid=:fromid AND cp.name LIKE ':info_product%' GROUP BY cp.cost_id )";
            $sql = strtr($sql, array(
                ':CostProduct' => CostProduct::$table,
                ':fromid' => Ak::getFormid() ,
                ':info_product' => $info_product,
            ));
            $criteria->addCondition($sql);
        }
        
        $criteria->compare('note', $this->note, true);
        $criteria->compare('status', $this->status);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    protected function beforeSave() {
        $itemid = $this->itemid;
        $result = parent::beforeSave();
        if ($this->isNewRecord && $itemid > 0) {
            $this->itemid = $itemid;
        }
        return $result;
        /*确保来自订单编号的可以政财界*/
    }
    protected function afterSave() {
        parent::afterSave();
        /**更新材料信息，同步材料规格信息，防止客户端篡改，不存在的材料设置其产品编号为０**/
        $data = array(
            ':materia' => CostMateria::$table,
            ':product' => Product::$table,
            ':table' => self::$table,
            ':itemid' => $this->itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sqls = array(
            'UPDATE :materia AS m,:product AS p SET
                            m.name = p.name,
                            m.spec = p.spec,
                            m.color = p.color,
                            m.unit = p.unit
                         WHERE 
                         m.fromid = :fromid AND m.cost_id = :itemid
                            AND m.fromid=p.fromid
                            AND m.product_id=p.itemid',
            'UPDATE :materia AS m SET
                            m.product_id = 0
                         WHERE 
                         m.fromid = :fromid AND m.cost_id = :itemid
                            AND m.product_id  NOT IN(SELECT itemid FROM :product WHERE fromid = :fromid)',
        );
        foreach ($sqls as $key => $sql) {
            $sql = strtr($sql, $data);
            // Tak::KD($sql);
            self::$db->createCommand($sql)->execute();
        }
        /**汇总更新总价,不用客户端提交的总价**/
        $this->upTotals();
        // exit;
        //查看编号是否存在于订单中，如果是,则更新status 为2,即订单转过来的,3生成生产单
        if ($this->status == 1 && $this->isOrder()) {
            $this->upStatus(2);
        }
    }
    /**
     * 更新状态为已经生成生产单
     * @return [type] [description]
     */
    public function upProduction() {
        $this->upStatus(3);
    }
    
    public function upStatus($status = 0) {
        $sql = strtr('UPDATE :table SET status=:status WHERE fromid = :fromid AND itemid=:itemid', array(
            ':table' => self::$table,
            ':status' => $status,
            ':fromid' => $this->fromid,
            ':itemid' => $this->itemid,
        ));
        self::$db->createCommand($sql)->execute();
    }
    /**
     * 判断编号，是不是订单转过来的
     * @param  integer $itemid [description]
     * @return boolean         [description]
     */
    public function isOrder($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        return Order::model()->findByPk($itemid) != null;
    }
    public function isProduction() {
        return $this->status == 2;
    }
    /**
     * 根据订单编号，查询订单的产品信息
     * @param  integer $itemid [description]
     * @return [type]          [description]
     */
    public function getOrderProduct($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT itemid,name AS type,model AS name,standard AS spec,color,amount FROM :product WHERE fromid = :fromid AND order_id=:itemid";
        $data = array(
            ':product' => OrderProduct::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        $result = self::$db->createCommand($sql)->queryAll(true);
        return $result;
    }
    /**
     * 根据成本核算编号，查询成本核算的产品信息
     * @param  integer $itemid 成本核算编号
     * @return array          产品数组
     */
    public function getProducts($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT * FROM :product WHERE fromid = :fromid AND cost_id=:itemid";
        $data = array(
            ':product' => CostProduct::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        $result = self::$db->createCommand($sql)->queryAll(true);
        return $result;
    }
    /**
     * 根据成本核算编号，分组查询成本核算主料１，辅料２
     * @param  integer $itemid [description]
     * @return [type]          [description]
     */
    public function getMaterias($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT * FROM :materia WHERE fromid = :fromid AND cost_id=:itemid ORDER BY typeid DESC";
        $data = array(
            ':materia' => CostMateria::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        // Tak::KD($sql, 1);
        $result = array(
            '1' => array() ,
            '2' => array() ,
        );
        $tags = self::$db->createCommand($sql)->queryAll();
        foreach ($tags as $value) {
            if ($value['product_id'] > 0) {
                $numbers = 0;
                if (isset($result[$value['typeid']][$value['product_id']])) {
                    $numbers = $result[$value['typeid']][$value['product_id']]['numbers'];
                }
                $value['numbers']+= $numbers;
                $result[$value['typeid']][$value['product_id']] = $value;
            } else {
                $result[$value['typeid']][] = $value;
            }
        }
        return $result;
    }
    /**
     * 生成材料的唯一编号,根据规格，产品编号，颜色，品名组合,汇总一下所有非库存产品的材料清单
     * @param  array $value 材料信息
     * @return string        最终组合
     */
    private function getMateId($value) {
        return implode('-', array(
            $value['name'],
            /*$value['typeid'], //主料，辅料*/
            $value['product_id'],
            $value['spec'],
            $value['color'],
            $value['unit'],
        ));
    }
    /**
     * 根据成本核算编号，查询所有材料及材料的库存信息
     * @param  integer $itemid [description]
     * @return [type]          [description]
     */
    public function getMateriasStocks($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT * FROM :materia WHERE fromid = :fromid AND cost_id=:itemid ORDER BY product_id DESC";
        $data = array(
            ':materia' => CostMateria::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        $tags = self::$db->createCommand($sql)->queryAll();
        $products = array();
        $result = array();
        /**保存产品信息，非库存中的材料为stocks -1**/
        foreach ($tags as $value) {
            $pid = $value['product_id'];
            if ($pid > 0) {
                $numbers = 0;
                if (isset($result[$pid])) {
                    $numbers = $result[$pid]['numbers'];
                }
                $value['numbers']+= $numbers;
                $value['stocks'] = 0;
                $result[$pid] = $value;
                $products[$pid] = $pid;
            } else {
                $value['stocks'] = - 1;
                $value['warehouse'] = array();
                $id = $this->getMateId($value);
                if (isset($result[$id])) {
                    $result[$id]['numbers']+= $value['numbers'];
                } else {
                    $result[$id] = $value;
                }
            }
        }
        /**读取仓库中的产品库存数**/
        if (count($products) > 0) {
            $pids = implode(',', $products);
            // Tak::KD($products);
            // Tak::KD($pids);
            $data[':productid'] = $pids;
            $data[':tabl'] = Stocks::$table;
            $sql = 'SELECT SUM(stocks) AS nums,product_id,0 AS warehouse FROM :tabl WHERE fromid = :fromid AND  product_id IN (:productid)  GROUP BY product_id UNION ALL  SELECT SUM(stocks) AS nums,product_id,warehouse_id AS warehouse FROM :tabl WHERE fromid = :fromid AND  product_id IN (:productid) AND warehouse_id>0 GROUP BY product_id,warehouse_id ';
            $sql = strtr($sql, $data);
            // Tak::KD($sql);
            $tags = self::$db->createCommand($sql)->queryAll();
            // Tak::KD($tags);
            $warehouses = Warehouse::getDatas(false);
            // Tak::KD($warehouses,1);
            foreach ($tags as $value) {
                $pid = $value['product_id'];
                $warehouse = $value['warehouse'];
                //总数
                if ($warehouse == 0) {
                    $result[$pid]['stocks'] = $value['nums'];
                } else {
                    //各个仓库中的数量
                    $result[$pid]['warehouse'][$warehouses[$warehouse]['name']] = $value['nums'];
                }
            }
        }
        return $result ? $result : array();
    }
    public function upTotals($itemid = 0, $notup = false) {
        $itemid == 0 && $itemid = $this->itemid;
        $data = array(
            ':table' => self::$table,
            ':materia' => CostMateria::$table,
            ':process' => CostProcess::$table,
            ':product' => CostProduct::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sqls = array(
            /**更新产品总价，汇总材料，工序总价**/
            'upp' => "UPDATE :product as p,(
                SELECT SUM(price*numbers) AS totals,cost_product_id AS itemid FROM :materia 
                    WHERE fromid=:fromid AND cost_id=:itemid 
                    GROUP BY cost_product_id
                ) AS m ,(
                SELECT SUM(price) AS totals,cost_product_id AS itemid FROM :process 
                    WHERE fromid=:fromid
                    GROUP BY cost_product_id
                ) AS po 
                    SET p.price = p.expenses,
                            p.price=p.price+m.totals,
                            p.price=p.price+po.totals ,
                            p.totals = p.price*p.numbers
                    WHERE 
                        p.fromid=:fromid 
                        AND p.cost_id=:itemid 
                        AND p.itemid=m.itemid
                        AND p.itemid = po.itemid",
            /**更新总价，来自产品总价的汇总**/
            "UPDATE :table as c,(
                        SELECT SUM(totals) AS totals FROM :product 
                            WHERE fromid=:fromid AND cost_id=:itemid) AS p 
                            SET c.totals = p.totals WHERE itemid=:itemid",
        );
        if ($notup) {
            unset($sqls['upp']);
        }
        foreach ($sqls as $key => $value) {
            
            $sql = strtr($value, $data);
            // Tak::KD($sql);
            self::$db->createCommand($sql)->execute();
        }
    }
    /**
     * 删除核算，如没保存成功的信息
     * @param  [type] $itemid [description]
     * @return [type]         [description]
     */
    public function del($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sqls = array(
            'DELETE FROM :process WHERE fromid=:fromid AND cost_product_id  IN(SELECT itemid FROM :product WHERE fromid=:fromid AND cost_id=:itemid)',
            'DELETE FROM :table WHERE fromid=:fromid AND itemid=:itemid',
            'DELETE FROM :product WHERE fromid=:fromid AND cost_id=:itemid',
            'DELETE FROM :materia WHERE fromid=:fromid AND cost_id=:itemid',
        );
        $db = self::$db;
        $data = array(
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
            
            ':table' => self::$table,
            ':product' => CostProduct::$table,
            ':materia' => CostMateria::$table,
            ':process' => CostProcess::$table,
        );
        foreach ($sqls as $key => $sql) {
            $sql = strtr($sql, $data);
            // Tak::KD($sql);
            $db->createCommand($sql)->execute();
        }
    }
}
