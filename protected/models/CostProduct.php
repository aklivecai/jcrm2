<?php
/**
 * This is the model class for table "{{Cost_Product}}".
 *
 * The followings are the available columns in table '{{Cost_Product}}':
 * @property string $itemid
 * @property string $fromid
 * @property string $cost_id
 * @property string $type
 * @property string $name
 * @property string $spec
 * @property string $color
 * @property string $file_path
 * @property string $expenses
 * @property string $price
 * @property string $numbers
 * @property string $totals
 */
class CostProduct extends DbRecod {
    public $linkName = 'name'; /*连接的显示的字段名字*/
    public static $table = '{{cost_product}}';
    public $scondition = ''; /*默认搜索条件*/
    public function rules() {
        return array(
            array(
                'cost_id, type, name, spec, numbers',
                'required'
            ) ,
            array(
                'itemid, cost_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, expenses,  totals',
                'length',
                'max' => 10
            ) ,
            array(
                'type, name, spec, color',
                'length',
                'max' => 100
            ) ,
            array(
                'file_path',
                'length',
                'max' => 255
            ) ,
            array(
                'totals,price,numbers',
                'length',
                'max' => 20
            ) ,
            array(
                'totals,price,numbers',
                'numerical',
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, fromid, cost_id, type, name, spec, color, file_path, expenses, price, numbers, totals',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'cost_id' => '成本核算编号',
            'type' => '品名',
            'name' => '型号',
            'spec' => '规格',
            'color' => '颜色',
            'file_path' => '产品图片',
            'expenses' => '管理费',
            'price' => '单价',
            'numbers' => '数量',
            'totals' => '总成本',
            'add_time' => '添加时间',
            'add_us' => '录入者',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
        );
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('cost_id', 0);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('spec', $this->spec, true);
        $criteria->compare('color', $this->color, true);
        $criteria->compare('expenses', $this->expenses, true);
        $criteria->compare('numbers', $this->numbers, true);
        $criteria->compare('totals', $this->totals, true);
        
        $times = array();
        $_time = isset($_GET['time']) ? $_GET['time'] : array();
        foreach ($_time as $key => $value) {
            $times[$key] = $value;
        }
        if (isset($times['add_time']) && count($times['add_time']) >= 1) {
            $add_times = $times['add_time'];
            $start = $add_times[0] ? Tak::getDayStart(strtotime($add_times[0])) : 0;
            $end = $add_times[1] > 0 ? Tak::getDayEnd(strtotime($add_times[1])) : 0;
            if ($start < 0 || $start > $end) {
                $start = $start > 0 ? $start : $end;
                if ($start > 0) {
                    $end = TaK::getDayEnd($start);
                }
            }
            if ($start > 0 && $end > $start) {
                $criteria->addBetweenCondition('add_time', $start, $end);
            }
        }
        
        $key = 'price';
        if ($this->$key > 0) {
            $val = floatval($this->$key);
            
            $v = $_GET['comparison'] ? $_GET['comparison'] : false;
            $comparison = TakType::items('comparison');
            if (!isset($comparison[$v])) {
                $v = '';
            }
            switch ($v) {
                case 'then':
                    $criteria->compare($key, $this->$key, true);
                break;
                case 'greater':
                    $criteria->addCondition("$key>$val");
                break;
                case 'less':
                    $criteria->addCondition("$key<$val");
                break;
                default:
                    $criteria->compare($key, $val);
                break;
            }
            // Tak::KD($criteria);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CostProduct the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //保存数据前
    protected function beforeSave() {
        $arr = Tak::getOM();
        if ($this->isNewRecord) {
            !$this->itemid && $this->itemid = $arr['itemid'];
            $this->fromid = $arr['fromid'];
            $this->add_us = $arr['manageid'];
            $this->add_time = $arr['time'];
            $this->add_ip = $arr['ip'];
        } else {
            //修改数据时候
            $this->modified_us = $arr['manageid'];
            $this->modified_time = $arr['time'];
            $this->modified_ip = $arr['ip'];
        }
        return true;
    }
    public function del($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $data = array(
            ':table' => self::$table,
            ':materia' => CostMateria::$table,
            ':process' => CostProcess::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sqls = array(
            ' DELETE FROM :materia WHERE fromid=:fromid AND cost_product_id=:itemid ',
            ' DELETE FROM :process WHERE fromid=:fromid AND cost_product_id=:itemid ',
            ' DELETE FROM :table WHERE fromid=:fromid AND itemid=:itemid ',
        );
        
        $command = self::$db->createCommand('');
        foreach ($sqls as $value) {
            Tak::KD(strtr($value, $data));
            $command->text = strtr($value, $data);
            $rowCount = $command->execute();
        }
    }
    /**
     * 根据成本核算编号，分组查询成本核算主料１，辅料２,工序价格
     * @param  integer $itemid [description]
     * @return [type]          [description]
     */
    public function getInfos($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $sql = "SELECT itemid,typeid,name,spec,color,unit,price,numbers,note,product_id FROM :materia WHERE fromid = :fromid AND cost_product_id=:itemid ORDER BY typeid DESC,itemid ASC";
        $data = array(
            ':materia' => CostMateria::$table,
            ':process' => CostProcess::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sql = strtr($sql, $data);
        // Tak::KD($sql, 1);
        $result = array(
            '1' => array() ,
            '2' => array() ,
            '3' => array() ,
        );
        $tags = self::$db->createCommand($sql)->queryAll();
        foreach ($tags as $value) {
            $result[$value['typeid']][] = $value;
        }
        $sql = "SELECT itemid,name,price,note FROM :process WHERE fromid = :fromid AND cost_product_id=:itemid ORDER BY itemid ASC";
        $sql = strtr($sql, $data);
        $tags = self::$db->createCommand($sql)->queryAll();
        foreach ($tags as $value) {
            $result[3][] = $value;
        }
        return $result;
    }
    public function upTotals($itemid = 0) {
        $itemid == 0 && $itemid = $this->itemid;
        $data = array(
            ':table' => self::$table,
            ':materia' => CostMateria::$table,
            ':process' => CostProcess::$table,
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
        );
        $sqls = array(
            /**更新产品总价，汇总材料，工序总价**/
            "UPDATE :table as p,(
                SELECT SUM(price*numbers) AS totals,cost_product_id AS itemid FROM :materia 
                    WHERE fromid=:fromid AND cost_product_id=:itemid 
                    GROUP BY cost_product_id
                ) AS m ,(
                SELECT SUM(price) AS totals,cost_product_id AS itemid FROM :process 
                    WHERE fromid=:fromid AND cost_product_id=:itemid 
                    GROUP BY cost_product_id
                ) AS po 
                    SET p.price = p.expenses,
                            p.price=p.price+m.totals,
                            p.price=p.price+po.totals ,
                            p.totals = p.price*p.numbers
                    WHERE 
                        p.fromid=:fromid 
                        AND p.itemid=:itemid 
                        AND p.itemid=m.itemid
                        AND p.itemid = po.itemid",
        );
        foreach ($sqls as $key => $value) {
            $sql = strtr($value, $data);
            self::$db->createCommand($sql)->execute();
        }
    }
}
