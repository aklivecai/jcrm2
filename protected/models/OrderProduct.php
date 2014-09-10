<?php
class OrderProduct extends MRecord {
    public static $table = '{{order_product}}';
    public function rules() {
        return array(
            array(
                'order_id, fromid, amount, price',
                'required'
            ) ,
            array(
                'itemid, order_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, amount, price, sum',
                'length',
                'max' => 10
            ) ,
            array(
                'name',
                'length',
                'max' => 100
            ) ,
            array(
                'model, standard, color, unit',
                'length',
                'max' => 50
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'itemid, order_id, fromid, name, model, standard, color, unit, amount, price, sum, note',
                'safe',
                'on' => 'search'
            ) ,
            
            array(
                'sum',
                'subTotal'
            ) ,
        );
    }
    /*计算产品总价*/
    public function subTotal($attribute = true, $params = true) {
        $result = 0;
        if ($this->price > 0 && $this->amount > 0) {
            $result = $this->price * $this->amount;
            $result = round($result, 2);
            // $result = Yii::app()->numberFormatter->formatDecimal($result);
            
            
        }
        $this->sum = $result;
        return $this->sum;
    }
    public function relations() {
        return array();
    }
    
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'order_id' => '订单编号',
            'fromid' => '平台商铺ID',
            'name' => '产品名称',
            'model' => '型号',
            'standard' => '规格',
            'color' => '颜色',
            'unit' => '单位',
            'amount' => '数量',
            'price' => '成交单价',
            'sum' => '总价',
            'note' => '备注',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('standard', $this->standard, true);
        $criteria->compare('color', $this->color, true);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('sum', $this->sum, true);
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
        $arr['order'] = ' itemid ASC ';
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave(true);
        if ($result) {
            //添加数据时候
            if ($this->isNewRecord) {
                $arr = Tak::getOM();
                if (!$this->itemid) {
                    $this->itemid = $arr['itemid'];
                }
            } else {
            }
        }
        return $result;
    }
    
    public $files = null;
    public function loadFiles() {
        if ($this->files == null) {
            $this->files = OrderFiles::model()->getListByActionID($this->itemid);
        }
        return $this->files;
    }
    
    public function getFilesImg($all = true) {
        $result = '';
        $files = $this->loadFiles();
        if (is_numeric($all)) {
            $all = $all >= count($files) ? count($files) : $all;
            for ($i = 0;$i < $all;$i++) {
                $result.= $files[0]->getLink();
            }
        } else {
            foreach ($files as $k1 => $v1) {
                $result.= $v1->getLink();
            }
        }
        return $result;
    }
    
    public function getListByOrder($condition) {
        echo '<div style="display:none;">';
        print_r(Ak::getDb('db'));
        print_r(self::$db);
        echo '</div>';
        
        $arr = array();
        foreach ($condition as $key => $value) {
            $arr[$key] = $value;
        }
        $list = $this->findAllByAttributes($arr);
        foreach ($list as $key => $value) {
            $value->loadFiles();
        }
        return $list;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
}
