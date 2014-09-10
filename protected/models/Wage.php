<?php
/**
 * 计件工资管理
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-07-02 09:03:21
 * @version $Id$
 */
class Wage extends DbRecod {
    public $linkName = 'name'; /*连接的显示的字段名字*/
    public static $table = '{{wage}}';
    public $scondition = ' status>0 ';
    public function rules() {
        return array(
            array(
                ' name, worker_id, amount, process_id, process, price, sum,complete_time',
                'required'
            ) ,
            array(
                'itemid, manageid, add_us, modified_us,worker_id,process_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid,complete_time, add_time, add_ip, modified_ip,status',
                'length',
                'max' => 10
            ) ,
            array(
                'amount,price,sum',
                'numerical',
            ) ,
            array(
                'name, process',
                'length',
                'max' => 200
            ) ,
            array(
                'serialid, note',
                'length',
                'max' => 255
            ) ,
            array(
                'company, product',
                'length',
                'max' => 100
            ) ,
            array(
                'model, standard, color, unit',
                'length',
                'max' => 50
            ) ,
            
            array(
                'name, worker_id, serialid, order_time, company, product, model, standard, color, unit, amount, process_id, process, price, sum, complete_time, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'order_time,complete_time',
                'checkTime'
            )
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '企业编号',
            'manageid' => '会员ID',
            'name' => '姓名', /*(工人)*/
            'worker_id' => '工人编号',
            'serialid' => '工单号',
            'order_time' => '下单日期',
            'company' => '公司',
            'product' => '产品',
            'model' => '型号',
            'standard' => '规格',
            'color' => '颜色',
            'unit' => '单位',
            'amount' => '数量',
            'process_id' => '工序编号',
            'process' => '工序', /*(名称)*/
            'price' => '工价', /*工序*/
            'sum' => '总价',
            'complete_time' => '完成日期',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
            'status' => '状态',
        );
    }
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('name', $this->name, true);
        $criteria->compare('company', $this->company, true);
        $criteria->compare('product', $this->product, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('serialid', $this->serialid, true);
        $criteria->compare('note', $this->note, true);
        
        $criteria->compare('add_time', $this->add_time);
        $criteria->compare('add_us', $this->add_us);
        $criteria->compare('add_ip', $this->add_ip);
        
        $times = array();
        $_time = isset($_GET['time']) ? $_GET['time'] : array();
        foreach ($_time as $key => $value) {
            $times[$key] = $value;
        }
        if (isset($times['order_time']) && count($times['order_time']) >= 1) {
            $add_times = $times['order_time'];
            $start = $add_times[0] ? Tak::getDayStart(strtotime($add_times[0])) : 0;
            $end = $add_times[1] > 0 ? Tak::getDayEnd(strtotime($add_times[1])) : 0;
            if ($start < 0 || $start > $end) {
                $start = $start > 0 ? $start : $end;
                if ($start > 0) {
                    $end = TaK::getDayEnd($start);
                }
            }
            if ($start > 0 && $end > $start) {
                $criteria->addBetweenCondition('order_time', $start, $end);
            }
        }
        
        if (isset($times['complete_time']) && count($times['complete_time']) >= 1) {
            $add_times = $times['complete_time'];
            $start = $add_times[0] ? Tak::getDayStart(strtotime($add_times[0])) : 0;
            $end = $add_times[1] > 0 ? Tak::getDayEnd(strtotime($add_times[1])) : 0;
            if ($start < 0 || $start > $end) {
                $start = $start > 0 ? $start : $end;
                if ($start > 0) {
                    $end = TaK::getDayEnd($start);
                }
            }
            if ($start > 0 && $end > $start) {
                $criteria->addBetweenCondition('complete_time', $start, $end);
            }
        }
        //金额
        if ($this->sum > 0) {
            $sum = floatval($this->sum);
            $v = $_GET['comparison'] ? $_GET['comparison'] : false;
            $comparison = TakType::items('comparison');
            if (!isset($comparison[$v])) {
                $v = '';
            }
            switch ($v) {
                case 'then':
                    $criteria->compare('sum', $this->sum, true);
                break;
                case 'greater':
                    $criteria->addCondition("sum>$sum");
                break;
                case 'less':
                    $criteria->addCondition("sum<$sum");
                break;
                default:
                    $criteria->compare('sum', $sum);
                break;
            }
        }
        
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
    }
    protected function afterSave() {
        parent::afterSave();
    }
    
    public $totals = 0;
    public function getData($ym, $w = false, $page = 0, $pagesize = 15) {
        $wheres = array(
            'fromid=:fromid',
            'status>0',
            'complete_time BETWEEN :f AND :t',
        );
        if ($w) {
            $wheres[] = sprintf("name='%s'", $w);
        }
        $data = array(
            ':itemid' => $itemid,
            ':fromid' => Ak::getFormid() ,
            ':table' => self::$table,
            ':f' => strtotime($ym . '-1-1 00:00:00') ,
            ':t' => strtotime(($ym + 1) . '-1-1 00:00:00') ,
            ':page' => $page,
            ':size' => $pagesize,
            ':tableWorker' => DepartmentWorker::$table,
        );
        $db = self::$db;
        $sqlWhere = sprintf(' FROM :table WHERE  %s', implode(' AND ', $wheres));
        //这个时间段有多少个人
        $sql = sprintf('SELECT count(1) %s GROUP BY worker_id ', $sqlWhere);
        $sql = strtr($sql, $data);
        $this->totals = count($db->createCommand($sql)->queryColumn());
        if ($this->totals == 0) {
            return array();
        }
        /*汇总这段时间分页好的　几个人的所有工资*/
        $sql = sprintf('SELECT worker_id,sum,complete_time %s ORDER BY worker_id ASC LIMIT :page,:size', $sqlWhere);
        $sql = strtr($sql, $data);
        
        $datas = $db->createCommand($sql)->queryAll();
        $workers = array();
        foreach ($datas as $key => $value) {
            $m = date('m', $value['complete_time']);
            if (!isset($workers[$value['worker_id']]['data'][$m])) {
                $workers[$value['worker_id']]['data'][$m] = 0;
            }
            $workers[$value['worker_id']]['data'][$m]+= $value['sum'];
            //13表示最后一列的汇总
            $workers[$value['worker_id']]['data']['13']+= $value['sum'];
        }
        // Tak::KD($workers);
        /*查询相关工人的名字*/
        $keys = array_keys($workers);
        $sql = sprintf('SELECT itemid,name FROM :tableWorker WHERE fromid=:fromid AND itemid IN(%s)', implode(',', $keys));
        $sql = strtr($sql, $data);
        
        $datas = $db->createCommand($sql)->queryAll();
        foreach ($datas as $key => $value) {
            $workers[$value['itemid']]['name'] = $value['name'];
            $_ms = array();
            for ($i = 1;$i <= 13;$i++) {
                $m = $i < 10 ? '0' . $i : $i;
                $_ms[$m] = isset($workers[$value['itemid']]['data'][$m]) ? $workers[$value['itemid']]['data'][$m] : '';
            }
            $workers[$value['itemid']]['data'] = $_ms;
        }
        return $workers;
    }
}
