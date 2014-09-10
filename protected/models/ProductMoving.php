<?php
class ProductMoving extends CActiveRecord {
    private $products = null;
    private $total = '0.00';
    public static $table = '{{product_moving}}';
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    public function rules() {
        return array(
            array(
                'itemid, movings_id, product_id, numbers,warehouse_id,type',
                'required'
            ) ,
            array(
                'itemid,fromid,movings_id, product_id,price',
                'length',
                'max' => 25
            ) ,
            array(
                'numbers,warehouse_id,time_stocked',
                'length',
                'max' => 10
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, type, movings_id, product_id,warehouse_id, numbers, note',
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
            'iMovings' => array(
                self::BELONGS_TO,
                'Movings',
                'movings_id'
            ) ,
        );
    }
    
    public function getTotal() {
        $result = 0;
        if ($this->price > 0 && $this->numbers != 0) {
            $result = $this->price * $this->numbers;
        }
        return $result;
    }
    /**
     * 查询出入库的产品记录
     * @param  int  $typeid     类型
     * @param  int $product_id 产品编号
     * @return [type]              [description]
     */
    public function getProductMovings($typeid, $product_id = 0, $warehouse_id = 0) {
        $condition = array(
            ' time_stocked > 0 '
        );
        if ($typeid > 0) {
            $condition[] = " type = $typeid ";
        }
        if ($product_id > 0) {
            $condition[] = " product_id = '$product_id' ";
        }
        if ($warehouse_id) {
            if (is_array($warehouse_id)) {
                $warehouse = sprintf("warehouse_id IN(%s)", implode(',', $warehouse_id));
                $this->warehouse_id = - 1;
            } elseif ($warehouse_id > 0) {
                $warehouse = sprintf("warehouse_id =%s", $warehouse_id);
            }
        }
        if ($warehouse) {
            $condition[] = $warehouse;
        }
        
        $dataProvider = new CActiveDataProvider('ProductMoving', array(
            'criteria' => array(
                'condition' => implode(" AND ", $condition) ,
            ) ,
        ));
        // $this->products = $dataProvider;
        // Tak::KD($this->product_movings,1);
        return $dataProvider;
    }
    
    public function attributeLabels() {
        return array(
            'itemid' => '编号', /*(可前端生成)*/
            'type' => '类型', /*(1:入库|2:出库)*/
            'time_stocked' => '确认操作日期',
            'movings_id' => '出入库号',
            'product_id' => '产品',
            'warehouse_id' => '仓库',
            'price' => '价格',
            'numbers' => '数量',
            'note' => '备注',
        );
    }
    
    public function getPageSize() {
        if (isset($_GET['setPageSize'])) {
            $setPageSize = (int)$_GET['setPageSize'];
            if ($setPageSize >= 0 && $setPageSize != Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize'])) {
                Yii::app()->user->setState('pageSize', $setPageSize);
            }
            unset($_GET['setPageSize']);
            $pageSize = $setPageSize;
        } else {
            $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        }
        return $pageSize;
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        $pageSize = $this->getPageSize();
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('type', $this->type);
        $criteria->compare('movings_id', $this->movings_id);
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('numbers', $this->numbers, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('note', $this->note, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ) ,
        ));
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = array(
            'order' => 'itemid DESC',
        );
        $condition = array();
        if (isset($arr['condition'])) {
            $condition[] = $arr['condition'];
        }
        // $condition[] = 'display>0';
        
        $sql = implode(" AND ", $condition);
        str_replace(" type = '1' AND type", "type", $sql);
        $arr['condition'] = $sql;
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
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
    
    public function recentlyByMovingid($movings_id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'movings_id = ' . $movings_id,
        ));
        return $this;
    }
    
    public static function getListByMovingid($movings_id) {
        $criteria = new CDbCriteria;
        $criteria->addCondition("movings_id=$movings_id");
        // $criteria->select = 'id,parentid,name'; //代表了要查询的字段，默认select='*';
        // $criteria->join = 'xxx'; //连接表
        $criteria->with = 'iProduct'; //调用relations
        // $criteria->join = 'LEFT JOIN Product ON Product.itemid = t.product_id';
        $criteria->limit = 999; //取1条数据，如果小于0，则不作处理
        // $criteria->order = 'xxx DESC,XXX ASC' ;//排序条件
        $t = self::model();
        $t->setDbCriteria($criteria);
        // return $t->findAll();
        
        $arr = array(
            ':movings_id' => $movings_id,
            ':product_moving' => '{{product_moving}}',
            ':product' => '{{product}}',
        );
        $sql = ' SELECT p.itemid,p.name,pm.numbers,pm.note FROM :product AS p LEFT JOIN :product_moving  AS pm ON(p.itemid=pm.product_id) WHERE pm.movings_id=:movings_id';
        $sql = strtr($sql, $arr);
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
        $reader = $command->query();
        $arr = array();
        foreach ($reader as $key => $row) {
            $arr[$row['itemid']] = $row;
        }
        return $arr;
    }
}
