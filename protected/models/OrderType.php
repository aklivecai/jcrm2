<?php
class OrderType {
    //信息状态
    const STATUS_DELETED = 0;
    const STATUS_DEFAULT = 1;
    //是否公开
    const DISPLAY_PRITAVE = 0;
    const DISPLAY_DEFAULT = 1;
    
    private static $_items = array(
        'order-status' => array(
            '1' => '待审核',
            '200' => '取消订单',
            '10' => '订单变更(待审核)',
            '11' => '订单变更(通过)',
            '12' => '订单变更(不通过)',
            '101' => '订单审核(通过)',
            '102' => '订单生产(付款正常)',
            '103' => '订单发货',
            // '202'=>'确认收货',
            '999' => '订单完成',
        ) ,
        // 交货方式
        'detype' => array(
            '' => '交货方式',
            '1' => '自提',
            '2' => '供方送货',
            '3' => '买方指定货运'
        ) ,
        // 支付方式
        'pay_type' => array(
            '' => '支付方式',
            '1' => '电汇',
            '2' => '现金',
            '3' => '转账'
        ) ,
        // 包装要求
        'packing' => array(
            '' => '包装要求',
            '1' => '常规包装',
            '2' => '纸箱包装',
            '3' => '木箱包装'
        ) ,
        // 是否包含税
        'taxes' => array(
            '' => '是否包含税',
            '1' => '包含税金',
            '2' => '不包含税金'
        ) ,
        // 是否包含运输和安装费
        'convey' => array(
            '' => '是否包含运输和安装费',
            '1' => '包含运输和安装费',
            '2' => '不包含运输和安装费'
        ) ,
        'area' => array(
            "" => "所在地区",
            "1" => "北京",
            "2" => "上海",
            "3" => "天津",
            "4" => "重庆",
            "5" => "河北",
            "6" => "山西",
            "7" => "内蒙古",
            "8" => "辽宁",
            "9" => "吉林",
            "10" => "黑龙江",
            "11" => "江苏",
            "12" => "浙江",
            "13" => "安徽",
            "14" => "福建",
            "15" => "江西",
            "16" => "山东",
            "17" => "河南",
            "18" => "湖北",
            "19" => "湖南",
            "20" => "广东",
            "21" => "广西",
            "22" => "海南",
            "23" => "四川",
            "24" => "贵州",
            "25" => "云南",
            "26" => "西藏",
            "27" => "陕西",
            "28" => "甘肃",
            "29" => "青海",
            "30" => "宁夏",
            "31" => "新疆",
            "32" => "台湾",
            "33" => "香港",
            "34" => "澳门"
        ) ,
        
        'label' => array(
            '0' => '',
            '1' => 'label-success',
            '2' => 'label-warning',
            '3' => 'label-important',
            '4' => 'label-info',
            '5' => 'label-inverse'
        )
    );
    
    public static function getStatus($type, $typeid, $fromid = 0) {
        $content = '';
        if (!isset(self::$_items[$type])) self::loadItems($type, $fromid);
        $content = isset(self::$_items[$type][$typeid]) ? self::$_items[$type][$typeid] : false;
        if ($content) {
            $className = 'label ';
            $className.= self::$_items['label'][$typeid];
            $content = CHtml::tag('span', array(
                'class' => $className
            ) , $content);
        }
        return $content;
    }
    
    public static function items($type, $fromid = 0) {
        if (!isset(self::$_items[$type])) self::loadItems($type, $fromid);
        return self::$_items[$type];
    }
    
    public static function sitems($type, $title = false, $fromid = 0) {
        
        if (!isset(self::$_items[$type])) {
            $arr = self::loadItems($type, $fromid);
        } else {
            $arr = self::$_items[$type];
        }
        if (!isset($arr[$type]['-1'])) {
            $temp = array();
            $temp['-1'] = $title ? $title : Tk::g(array(
                'Select',
                $type
            ));
            foreach ($arr as $key => $value) {
                $temp[$key] = $value;
            }
            $arr = $temp;
        }
        return $arr;
    }
    
    public static function item($type, $typeid, $fromid = 0) {
        $result = '';
        if (!isset(self::$_items[$type])) self::loadItems($type, $fromid);
        $result = isset(self::$_items[$type][$typeid]) ? self::$_items[$type][$typeid] : false;
        if ($result && $typeid == 200) {
            $result = str_replace("订单", '', "已经$result");
        }
        return $result;
    }
    
    private static function loadItems($type, $fromid = 0) {
        self::$_items[$type] = array();
        if (!is_numeric($fromid)) {
            $fromid = Tak::getFormid();
        }
        $models = self::model()->findAll(array(
            'condition' => 'item=:item AND fromid=:fromid',
            'params' => array(
                ':item' => $type,
                ':fromid' => $fromid
            ) ,
            'order' => 'listorder DESC,typeid ASC',
        ));
        $tags = array();
        foreach ($models as $model) $tags[$model->typeid] = $model->typename;
        self::$_items[$type] = $tags;
        
        return $tags;
    }
    
    public static function getItems() {
        return self::$_items;
    }
}
