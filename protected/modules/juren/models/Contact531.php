<?php
/**
 * 这个模块来自表 "{{contact531}}".
 *
 * 数据表的字段 '{{contact531}}':
 * @property string $itemid
 * @property string $fromid
 * @property string $manageid
 * @property string $clientele_name
 * @property string $nicename
 * @property string $phone
 * @property string $mobile
 * @property string $address
 * @property string $web
 * @property string $business
 * @property string $add_time
 * @property string $add_us
 * @property string $add_ip
 * @property string $modified_time
 * @property string $modified_us
 * @property string $modified_ip
 * @property string $note
 */
class Contact531 extends DbRecod {
    
    public $linkName = 'contact_time';
    public static $table = '{{contact531}}';
    /**
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'clientele_name, nicename',
                'required'
            ) ,
            array(
                'itemid, manageid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'clientele_name',
                'length',
                'max' => 100
            ) ,
            array(
                'nicename',
                'length',
                'max' => 64
            ) ,
            array(
                'phone, mobile, address, business, note',
                'length',
                'max' => 255
            ) ,
            array(
                'web',
                'length',
                'max' => 50
            ) ,
            
            array(
                'status,fromid',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'mobile',
                'checkMobile'
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, fromid, manageid, clientele_name, nicename, phone, mobile, address, web, business, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note,status',
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
    public function checkName($attribute, $params) {
    }
    
    public function checkMobile($attribute, $params) {
        if ($this->mobile == '' && $this->phone == '') {
            $this->addError('mobile', '手机或者办公电话至少有填一个');
            return false;
        }
        $sql = ' 1=1 ';
        /*
        $time = Ak::now();
        $date = date("Y-m-d", $time);
        $dayEnd = strtotime($date . " 23:59:59");
        $dayStart = strtotime($date . " 00:00:01");       
        $sql = sprintf('(add_time BETWEEN %s  AND %s)', $dayStart, $dayEnd);
        */
        if ($this->primaryKey > 0) {
            $sql.= sprintf(' AND itemid<>%s  ', $this->primaryKey);
        }
        foreach (array(
            'mobile',
            'phone',
            'clientele_name'
        ) as $value) {
            if ($this->$value != '') {
                $_sql = $sql . " AND  $value=:val";
                $m = $this->find($_sql, array(
                    ':val' => $this->$value
                ));
                if ($m != null) {
                    $str = sprintf("%s:%s 已经存在", $this->getAttributeLabel($value) , $this->$value);
                    break;
                }
            }
        }
        
        if ($str) {
            $this->addError('', $str);
            $result = false;
        } else {
            $sql = array(
                sprintf('fromid=%s', Ak::getFormid()) ,
                sprintf("LOWER(clientele_name)='%s'", strtolower(addslashes($this->clientele_name))) ,
            );
            $sql = implode(' AND ', $sql);
            // $sql = strtr($sql, $arr);
            $sql = sprintf('SELECT COUNT(1) FROM %s WHERE %s', Clientele::$table, $sql);
            $command = self::$db->createCommand($sql);
            $dataReader = $command->queryScalar();
            // Tak::KD($m,1);
            $result = true;
            if ($dataReader > 0) {
                $err = sprintf('%s: %s 已经存在系统客户模块中', $this->getAttributeLabel('clientele_name') , $this->clientele_name);
                $this->addError('clientele_name', $err);
                $result = false;
            }
        }
        return $result;
    }
    /**
     * @return array customized attribute labels (name=>label) 字段显示的
     */
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '企业编号',
            'manageid' => '会员ID',
            'clientele_name' => '客户名字',
            'nicename' => '联系人',
            'phone' => '办公电话',
            'mobile' => '手机',
            'address' => '联系地址',
            'web' => '网站',
            'business' => '主营产品',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'status' => '状态', /*(0:回收站,1:正常)*/
            'note' => '备注',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('itemid', $this->itemid, true);
        $criteria->compare('fromid', $this->fromid, true);
        $criteria->compare('manageid', $this->manageid, true);
        $criteria->compare('clientele_name', $this->clientele_name, true);
        $criteria->compare('nicename', $this->nicename, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('web', $this->web, true);
        $criteria->compare('business', $this->business, true);
        if (!$this->add_time) {
            $this->add_time = date("Y-m-d", Ak::now());
        }
        $this->setCriteriaTime($criteria, array(
            'add_time',
        ));
        $criteria->compare('note', $this->note, true);
        return $cActive;
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
        $arr['condition'] = join(" AND ", $condition);
        return $arr;
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            //添加数据时候
            if ($this->isNewRecord) {
            } else {
            }
        }
        return $result;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
}
