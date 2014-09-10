<?php
/**
 * 这个模块来自表 "{{production_product}}".
 *
 * 数据表的字段 '{{production_product}}':
 * @property string $production_id
 * @property string $fromid
 * @property string $product_id
 * @property string $workshop_id
 */
class ProductionProduct extends DbRecod {
    public static $table = '{{production_product}}';
    public function primaryKey() {
        return array(
            'fromid',
            'production_id',
            'product_id',
            'workshop_id'
        );
    }
    public function rules() {
        return array(
            array(
                'production_id,product_id, workshop_id',
                'required'
            ) ,
            array(
                'production_id, product_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, workshop_id',
                'length',
                'max' => 10
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'production_id, product_id, workshop_id',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'production_id' => '生产单编号',
            'fromid' => '平台会员ID',
            'product_id' => '产品编号', /*(对应成本核算产品)*/
            'workshop_id' => '车间编号',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('production_id', $this->production_id, true);
        $criteria->compare('fromid', $this->fromid, true);
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('workshop_id', $this->workshop_id, true);
        return $cActive;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = array();
        return $arr;
    }
    //保存数据前
    protected function beforeSave() {
        $result = true;
        $this->fromid = Ak::getFormid();
        return $result;
    }
    //保存数据后,不用记录日志
    protected function afterSave() {
        /*parent::afterSave();*/
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }    
}
