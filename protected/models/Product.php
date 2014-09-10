<?php
class Product extends DbRecod {
    public static $table = '{{product}}';
    public $linkName = 'name'; /*连接的显示的字段名字*/
    protected $_stocks = null;
    
    public $price = '0.00';
    private $total = '0.00';
    private $stock = 0;
    
    public $warehouse_id = false;
    public function rules() {
        return array(
            array(
                ' name, typeid',
                'required'
            ) ,
            array(
                'status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'price',
                'numerical',
            ) ,
            array(
                'stocks,itemid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, typeid, unit, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'name, material, spec,color',
                'length',
                'max' => 100
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'itemid, fromid, name, typeid, material, spec, unit, stocks, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note, status,warehouse_id',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function relations() {
        $condition = array(
            "module='product'"
        );
        // $condition[] = 'fromid=' . Tak::getFormid();
        $sqlStocks = $this->getConAlias('itemid=iStocks.product_id');
        return array(
            'iType' => array(
                self::BELONGS_TO,
                'Category',
                'typeid',
                'select' => 'catename',
                'condition' => implode(" AND ", $condition)
            ) ,
            'iStocks' => array(
                self::HAS_ONE,
                'Stocks'
                // , 'itemid'
                // , 'product_id'
                ,
                '',
                'condition' => '',
                'order' => '',
                'select' => 'itemid,stocks,modified_time',
                'on' => $sqlStocks
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '物料编号',
            'fromid' => '平台会员ID',
            'name' => '产品型号',
            'typeid' => '货物分类',
            'material' => '材料',
            'spec' => '规格',
            'color' => '颜色',
            'unit' => '计量单位',
            'stocks' => '库存', /*(可负)*/
            'price' => '单价',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '介绍',
            
            'status' => '状态', /*(0:回收站,1:正常)*/
            
            'warehouse_id' => ' 仓库',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('name', $this->name, true);
        if ($this->typeid > 0) {
            $cates = Category::getCatsProduct();
            if (isset($cates[$this->typeid])) {
                if ($cates[$this->typeid]['arrchildid'] > 0) {
                    $criteria->addInCondition('typeid', explode(',', $cates[$this->typeid]['arrchildid']));
                } else {
                    $criteria->compare('typeid', $this->typeid);
                }
            }
        }
        
        $criteria->compare('material', $this->material, true);
        $criteria->compare('spec', $this->spec, true);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('color', $this->color, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('stocks', $this->stocks);
        $criteria->compare('add_time', $this->add_time);
        $criteria->compare('add_us', $this->add_us);
        $criteria->compare('add_ip', $this->add_ip);
        $criteria->compare('modified_time', $this->modified_time);
        $criteria->compare('modified_us', $this->modified_us);
        $criteria->compare('modified_ip', $this->modified_ip);
        $criteria->compare('note', $this->note, true);
        
        $criteria->compare('status', $this->status);
        /*仓库管理员，只查询自己在信息*/
        // !$this->warehouse_id && !is_array($this->warehouse_id) && $this->warehouse_id = $_GET['warehouse_id'];
        
        $warehouse_id = $this->warehouse_id;
        if (is_array($warehouse_id)) {
            count($warehouse_id) == 0 && $warehouse_id = array(-1
            );
            $warehouse = sprintf("warehouse_id IN(%s)", implode(',', $warehouse_id));
            $this->warehouse_id = - 1;
        } elseif ($warehouse_id > 0) {
            $warehouse = sprintf("warehouse_id =%s", $warehouse_id);
        }
        
        if ($warehouse) {
            $sql = sprintf("itemid in (SELECT product_id FROM %s WHERE fromid=%s AND $warehouse  GROUP BY itemid)", Stocks::$table, Tak::getFormid());
            $criteria->addCondition($sql);
        }
        
        return $cActive;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        if ($this->getDefaultScopeDisabled()) {
            return array();
        }
        $arr = array();
        if (func_num_args() > 0 && func_get_arg(0)) {
            $arr['order'] = $this->getConAlias('add_time DESC ');
        }
        $condition = array();
        if ($this->scondition && $this->scondition != '') {
            $condition[] = $this->getConAlias($this->scondition);
        }
        
        $condition[] = $this->getConAlias('fromid=' . Tak::getFormid());
        
        if ($this->getCu() && Tak::getManageid() != '44720284384568199') {
            $condition[] = $this->getConAlias('manageid=' . Tak::getManageid());
        }
        
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    
    public function getConAlias($sql) {
        $alias = false;
        if (property_exists($this, 'tableAlias') && $this->tableAlias) {
            $alias = $this->tableAlias;
        } else {
            /*$alias = 't';*/
        }
        if ($alias) {
            $sql = $alias . '.' . $sql;
        }
        return $sql;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $m = new Stocks;
            $m->product_id = $this->primaryKey;
            $m->stocks = 0;
            $m->status = 1;
            $m->save();
        }
    }
    
    protected function loadStock($type = 1) {
        if ($this->_stocks === null) {
            $m = Stocks::model();
            if ($type = 2) {
                $m->setRecycle(); /*搜索回收站*/
            }
            $this->_stocks = $m->findByAttributes(array(
                'product_id' => $this->primaryKey
            ));
        }
        
        return $this->_stocks;
    }
    public function del() {
        if (parent::del()) {
            $m = $this->loadStock();
            if ($m) {
                $m->del();
            }
        }
    }
    
    public function setRestore() {
        if (parent::setRestore()) {
            $m = $this->loadStock(2);
            if ($m) {
                $m->setRestore();
            }
        }
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
        $m = $this->loadStock();
        if ($m) {
            $m->delete();
        }
    }
    public function isDel() {
        $sql = " SELECT count(s.itemid) FROM :table  AS s
                  WHERE s.product_id = :itemid ";
        $sql = strtr($sql, array(
            ':table' => ProductMoving::$table,
            ':itemid' => $this->itemid,
        ));
        $count = self::$db->createCommand($sql)->queryScalar();
        return $count;
    }
    protected function beforeDelete() {
        $result = true;
        if ($result && $this->isDel() > 0) {
            $result = false;
            Tak::setFlash('产品已经有出入库记录，不允许删除!', 'error');
        }
        return $result;
    }
    
    public function getTotal() {
        $result = 0;
        if ($this->price > 0 && $this->getStock() != 0) {
            $result = $this->price * $this->getStock();
        }
        return $result;
    }
    private $_stock = null;
    public function getStock() {
        if ($this->_stock == null) {
            $warehouse_id = $_GET['Product[warehouse_id]'];
            if ($warehouse_id>0&&!$warehouse_id && Permission::iSWarehouses()) {
                $warehouse_id = Warehouse::getUserWare();
            }
            $this->_stock = Stocks::getStocks($this->itemid, $warehouse_id);
            $this->_stock = Tak::getNums($this->_stock);
        } else {
        }
        return $this->_stock;
    }
    
    public static function getTotals($sql = false) {
        $model = self::model();
        $condition = $model->defaultScope(false);
        if (is_array($condition) && $condition['condition']) {
            $condition = array(
                $condition['condition']
            );
        } else {
            $condition = array();
        }
        if ($sql) {
            $condition[] = $sql;
        }
        $condition = implode(' AND ', $condition);
        $sql = ' SELECT  SUM(s.stotals) AS stotal,SUM(p.price*s.stotals) AS ptotal FROM :product p
            ,(SELECT SUM(stocks) AS stotals,product_id  FROM :stock WHERE product_id in ( 
                    SELECT itemid FROM :product WHERE :condition 
                )  GROUP BY product_id) s
            WHERE p.itemid = s.product_id ';
        
        $sql = strtr($sql, array(
            ':stock' => Stocks::$table,
            ':product' => self::$table,
            // ':productid'=>$productid,
            ':condition' => $condition ? $condition : ' 1=1 '
        ));
        
        $query = self::$db->createCommand($sql);
        $result = $query->queryRow();
        return $result;
    }
    
    public $history = null;
    public function writeHistory($type = 1, $warehouse_id = false) {
        $result = $this->getHistory($warehouse_id);
        // Ak::KD($result, 1);
        $html = isset($result[$type]) ? $result[$type] : 0;
        return Tak::getNums(sprintf('%01.4f', $html));
        return $html;
    }
    
    public function getHistory($warehouse_id = fale) {
        if ($this->history != null) {
            return $this->history;
        }
        $data = array(
            ':product_id' => $this->itemid,
            ':tabl' => ProductMoving::$table,
        );
        $condition = array(
            ' WHERE time_stocked>0 ',
            sprintf('product_id=%s', $this->itemid)
        );
        /* 查询仓库*/
        !$warehouse_id && $warehouse_id = $_GET['Product[warehouse_id]'];
        if ($warehouse_id) {
            if (is_array($warehouse_id)) {
                $warehouse = sprintf("warehouse_id IN(%s)", implode(',', $warehouse_id));
            } elseif ($warehouse_id > 0) {
                $warehouse = sprintf("warehouse_id =%s", $warehouse_id);
            }
        } else {
        }
        if ($warehouse) {
            $condition[] = $warehouse;
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
        // Tak::KD($sql,1);
        $query = self::$db->createCommand($sql)->queryAll();
        foreach ($query as $key => $value) {
            $arr[$value['itype']] = $value['total'];
        }
        $arr['1'] = $arr['21'] - $arr['22'];
        $arr['4'] = $arr['11'] - $arr['12'];
        $this->history = $arr;
        return $this->history;
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
}
