<?php
/**
 * This is the model class for table "{{cost_process}}".
 *
 * The followings are the available columns in table '{{cost_process}}':
 * @property string $itemid
 * @property string $fromid
 * @property string $cost_product_id
 * @property string $name
 * @property string $price
 * @property string $note
 */
class CostProcess extends DbRecod {
    public $linkName = 'name'; /*连接的显示的字段名字*/
    public static $table = '{{cost_process}}';
    public $scondition = ''; /*默认搜索条件*/
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                ' cost_product_id, name,price',
                'required'
            ) ,
            array(
                'itemid, cost_product_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid',
                'length',
                'max' => 10
            ) ,
            array(
                'price',
                'numerical',
            ) ,
            array(
                'name',
                'length',
                'max' => 60
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, fromid, cost_product_id, name, price, note',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '企业编号',
            'cost_product_id' => '成本核算产品编号',
            'name' => '工序名字',
            'price' => '价格',
            'note' => '备注',
        );
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //保存数据前
    protected function beforeSave() {
        if ($this->isNewRecord) {
            !$this->itemid && $this->itemid = Ak::fastUuid();
            !$this->fromid && $this->fromid = Ak::getFormid();
        }
        return true;
    }
    protected function afterSave() {
    }
}
