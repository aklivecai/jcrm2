<?php
class OrderInfo extends MRecord {
    public $earnest = '';
    public $few_day = '';
    public $delivery_before = '';
    public $remaining_day = '';
    
    public static $table = '{{order_info}}';
    
    public function rules() {
        
        return array(
            array(
                'company,detype, pay_type,earnest,few_day,delivery_before,remaining_day,packing,taxes,convey',
                'required'
            ) ,
            array(
                'detype, pay_type, earnest, few_day, delivery_before, remaining_day, packing, taxes, convey',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid',
                'length',
                'max' => 25
            ) ,
            array(
                'date_time, add_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'area, people, tel, phone, purchasconsign, contactphone',
                'length',
                'max' => 50
            ) ,
            
            array(
                'address',
                'length',
                'max' => 100
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'company',
                'length',
                'max' => 100
            ) ,
            array(
                'earnest, few_day, delivery_before, remaining_day',
                'length',
                'max' => 3
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'company,itemid, date_time, detype, pay_type, earnest, few_day, delivery_before, remaining_day, packing, taxes, convey, area, address, people, tel, phone, purchasconsign, contactphone, note, add_ip',
                'safe',
                'on' => 'search'
            ) ,
            
            array(
                'date_time',
                'checkTime'
            ) ,
        );
    }
    /**
     * @return array relational rules. 表的关系，外键信息
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'iOrderInfo' => array(
                self::HAS_ONE,
                'OrderInfo',
                'owner_id'
            ) ,
        );
    }
    /**
     * @return array customized attribute labels (name=>label) 字段显示的
     */
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'company' => '公司',
            'date_time' => '期望交货日期',
            'detype' => '交货方式',
            'pay_type' => '支付方式',
            'earnest' => '定金',
            'few_day' => '几天内支付',
            'delivery_before' => '交货前支付',
            'remaining_day' => '余额多少天支付',
            'packing' => '包装要求',
            'taxes' => '是否包含税',
            'convey' => '运输和安装费',
            'area' => '地区',
            'address' => '地址',
            'people' => '联系人',
            'tel' => '手机',
            'phone' => '电话',
            'purchasconsign' => '委托方',
            'contactphone' => '委托方联系方式',
            'note' => '备注要求',
            'add_ip' => '添加IP',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('itemid', $this->itemid, true);
        $criteria->compare('date_time', $this->date_time, true);
        $criteria->compare('detype', $this->detype);
        $criteria->compare('pay_type', $this->pay_type);
        $criteria->compare('earnest', $this->earnest);
        $criteria->compare('few_day', $this->few_day);
        $criteria->compare('delivery_before', $this->delivery_before);
        $criteria->compare('remaining_day', $this->remaining_day);
        $criteria->compare('packing', $this->packing);
        $criteria->compare('taxes', $this->taxes);
        $criteria->compare('convey', $this->convey);
        $criteria->compare('area', $this->area, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('people', $this->people, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('purchasconsign', $this->purchasconsign, true);
        $criteria->compare('contactphone', $this->contactphone, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('add_ip', $this->add_ip, true);
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
        // $condition[] = 'display>0';
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
                $this->add_ip = $arr['ip'];
            } else {
            }
        }
        return $result;
    }
    public function getPayInfo($total) {
        $result = ' &nbsp;&nbsp; 定金 <strong class="price-strong">:earnest%(%earnest)</strong> 于订单确认后 <strong>:few_day</strong>天内支付， 交货前支付 <strong class="price-strong">:delivery_before%(%delivery_before)</strong> %yk ';
        
        $earnest = $total * $this->earnest / 100;
        
        $delivery_before = $total * $this->delivery_before / 100;
        $yk = $total - $earnest - $delivery_before;
        if ($yk <= 0) {
            $yk = '，余款 <strong class="price-strong">0</strong>';
        } else {
            $yk = Tak::format_price($yk);
            
            $yk = sprintf('，余款(<span class="price-strong">%s</span>) <strong>%s</strong>天内支付', $yk, $this->remaining_day);
        }
        $earnest = Tak::format_price($earnest);
        $delivery_before = Tak::format_price($delivery_before);
        
        $result = strtr($result, array(
            ':earnest' => $this->earnest,
            ':few_day' => $this->few_day,
            ':delivery_before' => $this->delivery_before,
            
            '%earnest' => $earnest,
            '%delivery_before' => $delivery_before,
            '%yk' => $yk,
        ));
        
        return $result;
    }
    public function getContactp() {
        $result = '';
        $arr = false;
        if ($this->detype == '2') {
            $result = ' &nbsp;&nbsp; <strong>收货地址：</strong>:area - :address ';
            $result.= '，<strong>收货人：</strong> :people';
            if ($this->tel) $result.= '，<strong>手机：</strong>:tel';
            if ($this->phone) $result.= '，<strong>电话：</strong>:phone';
            $arr = array(
                ':area' => OrderType::item('area', $this->area) ,
                ':address' => $this->address,
                ':people' => $this->people,
                ':tel' => $this->tel,
                ':phone' => $this->phone,
            );
        } elseif ($this->purchasconsign) {
            $result = ' &nbsp;&nbsp; 购方委托 :purchasconsign 上门提货 , 联系电话 :contactphone';
            $arr = array(
                ':purchasconsign' => $this->purchasconsign,
                ':contactphone' => $this->contactphone,
            );
        } else {
            $result = '  &nbsp;&nbsp;' . OrderType::item('detype', $this->detype);
        }
        if ($arr) {
            $result = strtr($result, $arr);
        }
        return $result;
    }
}
