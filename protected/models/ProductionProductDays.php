<?php
/**
 * 这个模块来自表 "{{production_product_days}}".
 *
 * 数据表的字段 '{{production_product_days}}':
 * @property string $production_id
 * @property string $fromid
 * @property string $product_id
 * @property string $workshop_id
 * @property string $process
 * @property string $days
 */
class ProductionProductDays extends DbRecod {
    public static $table = '{{production_product_days}}';
    public function primaryKey() {
        return array(
            'fromid',
            'production_id',
            'workshop_id'
        );
    }
    public function rules() {
        return array(
            array(
                'production_id,product_id, workshop_id, process,days',
                'required'
            ) ,
            array(
                'production_id, product_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, workshop_id, days',
                'length',
                'max' => 10
            ) ,
            array(
                'process',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'production_id, product_id, workshop_id, process, days',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'production_id' => '生产单编号',
            'fromid' => '平台会员ID',
            'product_id' => '生产产品编号', /*(对应成本核算产品)*/
            'workshop_id' => '车间编号',
            'process' => '工序名字',
            'days' => ' 总用时 ', /*(0.5天)*/
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('production_id', $this->production_id, true);
        $criteria->compare('fromid', $this->fromid, true);
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('workshop_id', $this->workshop_id, true);
        $criteria->compare('process', $this->process, true);
        $criteria->compare('days', $this->days, true);
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
