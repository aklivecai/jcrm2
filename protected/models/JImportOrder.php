<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.org)
 * @date    2014-03-13 10:08:23
 * @version $Id$
 */
class JImportOrder extends JImportForm {
    
    public $headers = array(
        'A' => '下单日期',
        'B' => '工单号',
        'C' => '客户',
        'D' => '产品型号',
        'E' => '皮色',
        'F' => '规格',
        'G' => '数量',
        'H' => '单位',
        'I' => '备注要求',
        'J' => '包装',
        'K' => '出货日期',
        'L' => '单价',
    );
    public $cols = array(
        'A' => 'add_time', //下单日期
        
        'B' => 'serialid', //工单号
        'C' => 'company', //客户
        'D' => 'name', //产品型号
        'E' => 'color',
        'F' => 'standard', //规格
        'G' => 'amount', //数量
        'H' => 'unit', //单位
        'I' => 'note', //备注要求
        
        'J' => '包装',
        'K' => 'date_time', //出货日期
        'L' => 'price',
    );
    public $model = 'order';
    public function checkS($col, $value, $index) {
        $result = false;
        return $result;
        if (($col == 'A' || $col == 'B') && $value == '') {
            $result = true;
        } elseif ($col == 'I' && $value != '') {
            if (is_numeric($value) || $value == 0) {
                $v = $value;
                $result = !($v >= 0);
            } else {
                $result = true;
            }
        } elseif (($col == 'H' || $col == 'L') && $value != '') {
            $result = !Tak::isPrice($value);
        }
        return $result;
    }
    
    public function init() {
        parent::init();
    }
    private $newObjs = 0;
    public function import() {
        $arr = Tak::getOM();
        $itemid = $arr['itemid'];
        $logfile = sprintf('%s-test.log', Ak::getFormid());
        $strMsg = '';
        $newObjs = $this->newObjs;
        // 不记录日志
        AdminLog::$isLog = false;
        
        $order = new Order('create');
        $order->attributes = $arr;
        $order->manageid = 0;
        
        $orderInfo = new OrderInfo('create');
        $orderInfo->attributes = $arr;
        $orderInfo->attributes = array(
            'detype' => 0,
            'pay_type' => 0,
            'earnest' => 0,
            'few_day' => 0,
            'delivery_before' => 0,
            'remaining_day' => 0,
            'packing' => 0,
            'taxes' => 0,
            'convey' => 0,
        );
        
        $oproduct = new OrderProduct('create');
        $oproduct->attributes = $arr;
        
        $data = $this->data;
        /*
        Tak::KD(count($_POST['order']) , 0, 'script');
        Tak::KD($data , 1, 'script');
        Tak::KD(count($data) , 1, 'script');
        */
        $transaction = Ak::db(true)->beginTransaction();
        try {
            foreach ($data as $key => $value) {
                $itemid = Ak::numAdd($itemid, $key + 2);
                $order->status = 101;
                $note = array();
                if ($value['包装']) {
                    $note[] = sprintf("包装:%s", $value['包装']);
                }
                $add_time = strtotime($value['add_time']);
                $date_time = strtotime($value['date_time']);
                if (!Ak::isTimestamp($add_time)) {
                    $add_time = time();
                }
                if (!Ak::isTimestamp($date_time)) {
                    $date_time = false;
                    $d = $value['date_time'];
                    //订单取消
                    if (strpos($d, '取消') !== false) {
                        $order->status = 200;
                    }
                    $note[] = sprintf("出货日期:%s", $d);
                } else {
                    $orderInfo->date_time = $date_time;
                }
                if (strlen($value['color']) > 45) {
                    $note[] = sprintf("颜色:%s", $value['color']);
                    $value['color'] = "看订单备注";
                }
                $orderInfo->note = $order->note = implode(',', $note);
                
                $order->add_time = $add_time;
                $order->serialid = $value['serialid'];
                $orderInfo->company = $order->company = $value['company'];
                if ($order->itemid > 0) {
                    $orderInfo->itemid = $order->itemid = $itemid;
                    $oproduct->itemid = $oproduct->order_id = $orderInfo->itemid;
                    
                    $order->setIsNewRecord(true);
                    $orderInfo->setIsNewRecord(true);
                    $oproduct->setIsNewRecord(true);
                }
                
                $oproduct->name = $value['name'];
                $oproduct->color = $value['color'];
                $oproduct->standard = $value['standard'];
                $oproduct->unit = $value['unit'];
                $oproduct->note = $value['note'];
                $oproduct->amount = isset($value['amount']) ? (float)$value['amount'] : 0;
                $oproduct->price = isset($value['price']) ? (float)$value['price'] : 0;
                
                if ($order->validate() && $orderInfo->validate() && $oproduct->validate()) {
                    $oproduct->save();
                    $order->total = $oproduct->sum;
                    $order->save();
                    $orderInfo->save();
                    
                    $order->saveStatus($order->status, '导入产品');
                    // Tak::KD($order->attributes);
                    // Tak::KD($orderInfo->attributes);
                    // Tak::KD($oproduct->attributes, 1);
                    unset($data[$key]);
                    $newObjs++;
                } else {
                    if ($order->validate()) {
                        $strMsg.= "\n" . var_export($order->attributes, TRUE);
                        $strMsg.= "\n" . var_export($order->getErrors() , TRUE);
                    }
                    if ($orderInfo->validate()) {
                        $strMsg.= "\n" . var_export($orderInfo->getErrors() , TRUE);
                        $strMsg.= "\n" . var_export($orderInfo->getErrors() , TRUE);
                    }
                    if ($oproduct->validate()) {
                        $strMsg.= "\n" . var_export($oproduct->getErrors() , TRUE);
                        $strMsg.= "\n" . var_export($oproduct->getErrors() , TRUE);
                    }
                    Tak::KD($strMsg, 1);
                }
            }
        }
        catch(Exception $e) {
            $transaction->rollback();
            $error = $e->getMessage();
            Tak::K($error, $logfile);
            if (strpos($error, 'Duplicate')) {
                $this->data = $data;
                $this->newObjs = $newObjs;
                $this->import();
            }
            return false;
        }
        $str = '
            <ul>
                <li>
                    成功导入订单：%s个<a href="%s">点击浏览</a>
                </li>%s
            </ul>
        ';
        
        $tarr = array(
            'add_ip' => $arr['ip'],
        );
        $url = Yii::app()->createUrl('Order/Admin?');
        $url.= Tak::createMUrl($tarr, $this->getModel());
        $str = sprintf($str, $newObjs, $url, $strCate);
        //删除导入数据缓存html
        if (!YII_DEBUG) {
            Tak::deleteUCache($this->model);
        }
        // 记录日志
        AdminLog::$isLog = true;
        //记录日志
        AdminLog::log($str, $arr);
        //设置提示消息，成功导入多少数据，新建多少分类
        Tak::setFlash($str);
        Tak::K(count($this->data) , $logfile);
        Tak::K($strMsg, $logfile);
    }
}
