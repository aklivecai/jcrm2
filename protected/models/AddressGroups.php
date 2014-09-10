<?php
class AddressGroups extends DbRecod {
    public static $table = '{{address_groups}}';
    public static $datas = null;
    public function primaryKey() {
        return 'address_groups_id';
    }
    public function rules() {
        return array(
            array(
                'name',
                'required'
            ) ,
            array(
                'display, listorder, status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'address_groups_id, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'name, note',
                'length',
                'max' => 255
            ) ,
            
            array(
                'address_groups_id, fromid, name, display, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note, listorder, status',
                'safe',
                'on' => 'search'
            ) ,
            array(
                'name',
                'checkRepetition'
            ) ,
        );
    }
    
    public function attributeLabels() {
        return array(
            'address_groups_id' => '编号',
            'fromid' => '平台会员ID',
            'name' => '名称',
            'display' => '显示', /*(0:自己,1：公共)*/
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
            'listorder' => '排序',
            'status' => '状态', /*(0:回收站,1:正常)*/
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('address_groups_id', $this->address_groups_id);
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('display', $this->display);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('listorder', $this->listorder);
        $criteria->compare('status', $this->status);
        return $cActive;
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        return $result;
    }
    
    public function getList($display = false) {
        $m = $this->findAll(array(
            'select' => 'address_groups_id,name',
            'condition' => $display ? 'display>0' : '',
        ));
        $items = array();
        $items = CHtml::listData($m, 'address_groups_id', 'name');
        return $items;
    }
    public function getLink($itemid = false, $action = 'view') {
        $markup = CHtml::link($this->name, array(
            'addressGroups/update',
            'id' => urlencode($this->address_groups_id) ,
        ));
        // $markup .= $this->sortableId();
        return $markup;
    }
    public function getLinkAddress() {
        $markup = CHtml::link($this->name, array(
            'AddressBook/Admin',
            'id' => urlencode($this->address_groups_id) ,
        ));
        // $markup .= $this->sortableId();
        return $markup;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //默认继承的搜索条件
    public function defaultScope() {
        $arr = parent::defaultScope();
        $condition = array(
            $arr['condition']
        );
        // $condition[] = 'display>0';
        $arr['condition'] = implode(" AND ", $condition);
        $arr['order'] = ' listorder DESC ';
        return $arr;
    }
    
    public static function getName($itemid) {
        $data = self::getDatas();
        $result = '';
        if (isset($data[$itemid])) {
            $result = $data[$itemid];
        }
        return $result;
    }
    
    public static function getDatas($iskey = true) {
        if (self::$datas == null || !$iskey) {
            $sql = "SELECT * FROM :table WHERE  fromid=:fromid ORDER BY listorder DESC, address_groups_id ASC";
            $sql = strtr($sql, array(
                ':table' => self::$table,
                ':fromid' => Tak::getFormid() ,
            ));
            
            $tags = Ak::db(true)->createCommand($sql)->queryAll(true);
            $result = array();
            foreach ($tags as $key => $value) {
                if ($iskey) {
                    $result[$value['address_groups_id']] = $value;
                } else {
                    $result[] = $value;
                }
            }
            self::$datas = $result;
        }
        return self::$datas;
    }
    
    public static function getDataProvider() {
        $data = self::getDatas(false);
        $dataProvider = new CArrayDataProvider($data, array(
            'id' => 'AddressGroups',
            'sort' => array(
                'attributes' => array(
                    'listorder',
                ) ,
            ) ,
            'pagination' => array(
                'pageSize' => 999,
            ) ,
        ));
        return $dataProvider;
    }
    public function isDel() {
        $sql = " SELECT count(s.itemid) FROM :table  AS s
                  WHERE s.fromid = :fromid AND s.groups_id = :itemid ";
        $sql = strtr($sql, array(
            ':table' => AddressBook::$table,
            ':fromid' => Tak::getFormid() ,
            ':itemid' => $this->primaryKey,
        ));
        $count = self::$db->createCommand($sql)->queryScalar();
        return $count;
    }
    public function del() {
        $result = false;
        $count = $this->isDel();
        if ($count > 0) {
            $result = '该分组已经有记录，不能进行删除';
        } else {
            $this->delete();
        }
        return $result;
    }
}
