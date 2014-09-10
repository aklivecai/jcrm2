<?php
/**
 * 这个模块来自表 "{{type}}".
 *
 * 数据表的字段 '{{type}}':
 * @property string $fromid
 * @property string $typeid
 * @property string $typename
 * @property string $item
 * @property integer $listorder
 */
class TakType extends DbiRecod {
    //信息状态
    const STATUS_DELETED = 0;
    const STATUS_DEFAULT = 1;
    //是否公开
    const DISPLAY_PRITAVE = 0;
    const DISPLAY_DEFAULT = 1;
    
    public $mName = ""; /*当前类名字*/
    public $sName = ""; /*显示名字*/
    
    private $scondition = false; /*默认搜索条件*/
    public static $table = '{{type}}';
    public function primaryKey() {
    }
    
    public function init() {
        $this->mName = get_class($this);
        $this->sName = Tk::g($this->mName);
    }
    
    public function initak($options = array()) {
        if (is_array($options)) {
            if ($options['name']) {
                $this->sName = Tk::g($options['name']) . $this->sName;
            }
            if ($options['type']) {
                $this->item = $options['type'];
                $this->setType($options['type']);
            }
        }
    }
    
    private static $_items = array(
        'status' => array(
            '0' => '锁定',
            '1' => '启用'
        ) ,
        'workstatus' => array(
            '1' => '停用',
            '2' => '启用'
        ) ,
        'isok' => array(
            '1' => '是',
            '0' => '否'
        ) ,
        'isbranch' => array(
            '0' => '普通员工',
            '1' => '部门经理'
        ) ,
        'display' => array(
            '1' => '公开',
            '0' => '私有'
        ) ,
        'sex' => array(
            '0' => '保密',
            '1' => '男',
            '2' => '女'
        ) ,
        'priority' => array(
            '0' => '低',
            '1' => '中',
            '2' => '高'
        ) ,
        'pageSize' => array(
            '0' => '显示',
            '5' => 5,
            '10' => 10,
            '20' => 20,
            '50' => 50,
            '100' => 100,
            '500' => 500,
        ) ,
        'filetype' => array(
            '0' => 'default',
            '2' => 'rar',
            '3' => 'doc',
            '4' => 'xls',
            '5' => 'txt'
        ) ,
        'label' => array(
            '0' => '',
            '1' => 'label-success',
            '2' => 'label-warning',
            '3' => 'label-important',
            '4' => 'label-info',
            '5' => 'label-inverse'
        ) ,
        'comparison' => array(
            'then' => '大概',
            'equals' => '等于',
            'greater' => '大于',
            'less' => '小于',
        )
    );
    
    public function setType($type) {
        $this->scondition = " item = '$type' ";
    }
    
    public static function getStatus($type, $typeid, $fromid = 0) {
        $content = '';
        if (!isset(self::$_items[$type])) self::loadItems($type, $fromid);
        $content = isset(self::$_items[$type][$typeid]) ? self::$_items[$type][$typeid] : false;
        if ($content) {
            $className = 'label ';
            if (isset(self::$_items['label'][$typeid])) {
                $className.= self::$_items['label'][$typeid];
            }
            
            $content = CHtml::tag('span', array(
                'class' => $className
            ) , $content);
        }
        return $content;
    }
    
    public static function loadGroups($type = 'AddressGroups') {
        $models = AddressGroups::model()->getList();
        foreach ($models as $key => $value) self::$_items[$type][$key] = $value;
    }
    
    public static function items($type, $fromid = 0, $strSub = '') {
        if (!isset(self::$_items[$type])) self::loadItems($type, $fromid, $strSub);
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
        if (!isset(self::$_items[$type])) self::loadItems($type, $fromid);
        return isset(self::$_items[$type][$typeid]) ? self::$_items[$type][$typeid] : false;
    }
    
    private static function loadItems($type, $fromid = 0, $strSub = '') {
        if ($type == 'AddressGroups') {
            self::loadGroups($type);
            return null;
        }
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
        if ($strSub != '') {
            $tags[''] = $strSub;
        }
        foreach ($models as $model) {
            $tags[$model->typeid] = $model->typename;
        }
        self::$_items[$type] = $tags;
        
        return $tags;
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $arr = array(
            'order' => 'listorder DESC,typeid ASC',
        );
        if ($this->scondition) {
            $condition = array(
                $this->scondition
            );
            $condition[] = 'fromid=' . Tak::getFormid();
            $arr['condition'] = implode(" AND ", $condition);
        }
        return $arr;
    }
    /**
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'item ,typename',
                'required'
            ) ,
            array(
                'listorder',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'fromid, typeid',
                'length',
                'max' => 10
            ) ,
            array(
                'typename',
                'length',
                'max' => 255
            ) ,
            array(
                'item',
                'length',
                'max' => 20
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'fromid, typeid, typename, item',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    /**
     * @return array relational rules. 表的关系，外键信息
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }
    /**
     * @return array customized attribute labels (name=>label) 字段显示的
     */
    public function attributeLabels() {
        $result = array(
            'fromid' => '平台会员ID',
            'typeid' => '值',
            'typename' => '名称',
            'item' => '类型',
            'listorder' => '排序',
        );
        if ($_GET['type'] == 'product') {
            $result['typename'] = Tk::g('Product Type');
        }
        return $result;
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('fromid', $this->fromid, true);
        $criteria->compare('typeid', $this->typeid, true);
        $criteria->compare('typename', $this->typename, true);
        $criteria->compare('item', $this->item, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TakType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            //添加数据时候
            if ($this->isNewRecord) {
                $this->typeid = time();
                $this->fromid = Tak::getFormid();
            } else {
            }
        }
        return $result;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
        $url = Yii::app()->request->getUrl();
        if (strpos($url, 'delete') > 0) {
            AdminLog::log(Tk::g('Deletes') . $this->sName);
        } elseif ($this->isNewRecord) {
            AdminLog::log(Tk::g('Create') . $this->sName . ' - 编号:' . $this->typeid);
        } else {
            AdminLog::log(Tk::g('Update') . $this->sName);
        }
    }
    
    protected function afterDelete() {
        parent::afterDelete();
        AdminLog::log(Tk::g('Deletes') . $this->sName);
    }
    
    public function getObj($typeid, $item) {
        $msg = $this->find('typeid=:typeid AND item=:item AND fromid=:fromid', array(
            ':typeid' => $typeid,
            ':item' => $item,
            ':fromid' => Tak::getFormid()
        ));
        return $msg;
    }
    
    public static function geList($item) {
        $result = self::model()->findAllByAttributes(array(
            'item' => $item,
            'fromid' => Tak::getFormid()
        ));
        return $result;
    }
    
    public function getEidtLink() {
        $link = Yii::app()->createUrl('takType/admin', array(
            'id' => $this->typeid,
            'type' => $this->item
        ));
        return $link;
    }
    
    public function getDelLink() {
        $link = Yii::app()->createUrl('takType/delete', array(
            'id' => $this->typeid,
            'type' => $this->item
        ));
        return $link;
    }
}
