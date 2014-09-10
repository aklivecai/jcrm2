<?php
/**
 * This is the model class for table "{{cost_materia}}".
 *
 * The followings are the available columns in table '{{cost_materia}}':
 * @property string $itemid
 * @property string $fromid
 * @property string $cost_id
 * @property string $product_id
 * @property string $typeid
 * @property string $name
 * @property string $spec
 * @property string $color
 * @property string $unit
 * @property string $price
 * @property string $numbers
 * @property string $note
 */
class CostMateria extends DbRecod {
    public $linkName = 'name'; /*连接的显示的字段名字*/
    public static $table = '{{cost_materia}}';
    public $scondition = ''; /*默认搜索条件*/
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'cost_id, cost_product_id,typeid, name, numbers',
                'required'
            ) ,
            array(
                'itemid, cost_id, product_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, typeid, unit',
                'length',
                'max' => 10
            ) ,
            array(
                'name, spec, color',
                'length',
                'max' => 100
            ) ,
            array(
                'price,numbers',
                'length',
                'max' => 20
            ) ,
            array(
                'price,numbers',
                'numerical',
            ) ,
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, fromid, cost_id, product_id, typeid, name, spec, color, unit, price, numbers, note',
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
            'fromid' => '企业编号',
            'cost_id' => '成本核算编号',
            'cost_product_id' => '成本核算产品编号',
            'product_id' => '产品编号(0非库存产品)',
            'typeid' => '类型(1.主料,2.辅料)',
            'name' => '型号',
            'spec' => '规格',
            'color' => '颜色',
            'unit' => '单位',
            'price' => '单价',
            'numbers' => '数量',
            'note' => '备注',
        );
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        
        $criteria = new CDbCriteria;
        
        $criteria->compare('itemid', $this->itemid, true);
        $criteria->compare('fromid', $this->fromid, true);
        $criteria->compare('cost_id', $this->cost_id, true);
        $criteria->compare('cost_product_id', $this->cost_id, true);
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('typeid', $this->typeid, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('spec', $this->spec, true);
        $criteria->compare('color', $this->color, true);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('numbers', $this->numbers, true);
        $criteria->compare('note', $this->note, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CostMateria the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //保存数据前
    protected function beforeSave() {
        if ($this->isNewRecord) {
            !$this->itemid && $this->itemid = Ak::fastUuid();
            !$this->fromid && $this->fromid = Ak::getFormid();
        }
        !$this->product_id && $this->product_id = 0;
        return true;
    }
    protected function afterSave() {
    }
}
