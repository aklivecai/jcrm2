<?php
class Stocks extends DbRecod {
    public static $table = '{{stocks}}';
    public function tableName() {
        return self::$table;
    }
    public function init() {
        parent::init();
        $this->isLog = false;
    }
    public function rules() {
        return array(
            array(
                'product_id',
                'required'
            ) ,
            array(
                'warehouse_id',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'stocks,itemid, product_id, add_us, modified_us',
                'length',
                'max' => 25
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
                'itemid, fromid, product_id, stocks, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function relations() {
        return array(
            'iProduct' => array(
                self::BELONGS_TO,
                'Product',
                'product_id',
                'condition' => '',
                'order' => ''
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'product_id' => '产品',
            'warehouse_id' => '仓库',
            'stocks' => '结存数量', /*(可负)*/
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
        
        $criteria->compare('warehouse_id', $this->warehouse_id);
        
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('stocks', $this->stocks);
        $criteria->compare('add_time', $this->add_time);
        $criteria->compare('add_us', $this->add_us);
        $criteria->compare('add_ip', $this->add_ip);
        $criteria->compare('modified_time', $this->modified_time);
        $criteria->compare('modified_us', $this->modified_us);
        $criteria->compare('modified_ip', $this->modified_ip);
        $criteria->compare('note', $this->note, true);
        return $cActive;
    }
    
    public static function getStocks($productid, $warehouse_id = false) {
        $sql = 'SELECT SUM(stocks) FROM :tabl WHERE product_id=:productid';
        /* 查询仓库*/
        if ($warehouse_id) {
            if (is_array($warehouse_id)) {
                $warehouse = sprintf("warehouse_id IN(%s)", implode(',', $warehouse_id));
            } elseif ($warehouse_id > 0) {
                $warehouse = sprintf("warehouse_id =%s", $warehouse_id);
            }
        } else {
        }
        if ($warehouse) {
            $sql.= " AND  $warehouse ";
        }
        $sql = strtr($sql, array(
            ':tabl' => self::$table,
            ':productid' => $productid,
        ));
        $query = self::$db->createCommand($sql);
        // $query->bindParam(":productid",$productid);
        // Tak::KD($sql);
        $count = $query->queryScalar();
        return $count;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        // $arr = parent::defaultScope();
        // $condition = array($arr['condition']);
        // $condition[] = ' product_id IN (SELECT itemid AS aid FROM {{product}} AS p WHERE p.status=1)';
        // $arr['condition'] = implode(" AND ",$condition);
        $arr = array(
            'condition' => $this->getConAlias('fromid=' . Tak::getFormid())
        );
        return $arr;
        return array();
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
    /**
     * 获取产品历史出入库数量
     * @param  int $product_id    产品编号
     * @param  int $warehouse_id 仓库编号
     * @return  string               上个月结存，本月出入库数量，本月结存
     */
    public static function getHistory($product_id, $warehouse_id = fale) {
        $data = array(
            ':product_id' => $product_id,
            ':tabl' => ProductMoving::$table,
        );
        $condition = array(
            ' WHERE 1=1',
            sprintf('product_id=%s', $product_id)
        );
        /* 查询仓库*/
        if ($warehouse_id > 0) {
            $data[':warehouse_id'] = $warehouse_id;
            $condition[] = sprintf('warehouse_id=%s', $warehouse_id);
        }
        
        $data[':where'] = implode(' AND ', $condition);
        $t = time();
        //
        $data[':本月开始时间'] = $t2 = mktime(0, 0, 0, date("m", $t) , 1, date("Y", $t));
        //
        $data[':本月结束时间'] = $e2 = mktime(23, 59, 59, date("m", $t) , date("t") , date("Y", $t));
        // 上个月开始时间
        $t3 = mktime(0, 0, 0, date("m", $t) - 1, 1, date("Y", $t));
        // 上个月结束时间
        $e3 = mktime(23, 59, 59, date("m", $t) - 1, date("t", $t3) , date("Y", $t));
        
        $sqls = array(
            '上个月进' => 'SELECT SUM(numbers) AS total,21 AS itype FROM :tabl  :where AND type=1 AND  time_stocked<:本月开始时间 GROUP BY product_id',
            '上个月出' => 'SELECT SUM(numbers) AS total,22 AS itype FROM :tabl  :where AND type=2   AND  time_stocked<:本月开始时间 GROUP BY product_id',
            
            '本月进货' => 'SELECT SUM(numbers) AS total,2 AS itype FROM :tabl  :where AND type=1 AND time_stocked>=:本月开始时间 GROUP BY product_id',
            '本月出货' => 'SELECT SUM(numbers) AS total,3 AS itype  FROM :tabl  :where AND type=2 AND time_stocked>=:本月开始时间  GROUP BY product_id',
            '结存进' => 'SELECT SUM(numbers) AS total,11 AS itype  FROM :tabl  :where AND type=1 AND time_stocked<=:本月结束时间 GROUP BY product_id',
            '结存出' => 'SELECT SUM(numbers) AS total,12 AS itype  FROM :tabl  :where AND type=2 AND time_stocked<=:本月结束时间 GROUP BY product_id',
        );
        $sql = implode(' UNION ALL ', $sqls);
        $sql = strtr($sql, $data);
        $arr = array(
            '1' => 0,
            '2' => 0,
            '3' => 0,
            
            '11' => 0,
            '12' => 0,
            '21' => 0,
            '22' => 0,
        );
        $query = self::$db->createCommand($sql)->queryAll();
        foreach ($query as $key => $value) {
            $arr[$value['itype']] = $value['total'];
        }
        $arr['1'] = $arr['21'] - $arr['22'];
        $arr['4'] = $arr['11'] - $arr['12'];
        
        $htmls = array(
            '<ul>'
        );
        $htmls[] = sprintf('<li>上个月结存：%01.4f</li>', $arr['1']);
        $htmls[] = sprintf('<li>本月进货：%01.4f</li>', $arr['2']);
        $htmls[] = sprintf('<li>本月出货：%01.4f</li>', $arr['3']);
        $htmls[] = sprintf('<li>本月结存：%01.4f</li>', $arr['4']);
        $htmls[] = '</ul>';
        return implode("\n", $htmls);
    }
    /**
     * 查询产品出入库数量
     * @param  int $productid [产品编号]
     * @return [type]            [description]
     */
    public static function getTypeStocks($productid) {
        $sql = ' SELECT SUM(numbers) AS total,type FROM :tabl  WHERE product_id=:productid AND time_stocked>0 :warehouse GROUP BY type';
        $warehouse = '';
        /*是不是仓库管理员*/
        if (Permission::iSWarehouses()) {
            $warehouse_id = Warehouse::getUserWare();
            if (is_array($warehouse_id)) {
                $warehouse = sprintf("AND warehouse_id IN(%s)", implode(',', $warehouse_id));
            } elseif ($warehouse_id > 0) {
                $warehouse = sprintf("AND warehouse_id =%s", $warehouse_id);
            }
        }
        $sql = strtr($sql, array(
            ':tabl' => ProductMoving::$table,
            ':productid' => $productid,
            ':warehouse' => $warehouse,
        ));
        
        $arr = array(
            '0' => self::getStocks($productid) ,
            '1' => 0,
            '2' => 0
        );
        $query = self::$db->createCommand($sql)->queryAll();
        foreach ($query as $key => $value) {
            $arr[$value['type']] = Tak::getNums($value['total']);
        }
        return $arr;
    }
}
