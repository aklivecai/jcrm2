<?php
/**
 *订单评价
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-25 11:30:23
 * @version $Id$
 */
class OrderReview extends CActiveRecord {
    public static $table = '{{order_review}}';
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        $m = get_class($this);
        return $m::$table;
    }
    public function primaryKey() {
        return 'itemid';
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '企业编号',
            'order_id' => '订单编号',
            'manageid' => '会员ID',
            'content' => '内容',
            'add_time' => '留言时间',
            'add_ip' => '添加IP',
        );
    }
    public function rules() {
        return array(
            array(
                'content',
                'required'
            ) ,
            array(
                'add_time,add_ip,order_id,itemid',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid, manageid,order_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, add_ip, add_time',
                'length',
                'max' => 10
            ) ,
            array(
                'itemid, fromid, manageid, content',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    //默认继承的搜索条件
    public function defaultScope() {
        return array();
    }
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($this->isNewRecord) {
            $arr = Ak::getOM();
            $this->itemid = $arr['itemid'];
            $this->manageid = $arr['manageid'];
            $this->add_time = $arr['time'];
            $this->add_ip = $arr['ip'];
        }
        return $result;
    }
}
