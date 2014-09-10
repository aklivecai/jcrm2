<?php
class Contact extends DbRecod {
    public $linkName = 'contact_time';
    public static $table = '{{contact}}';
    
    public function rules() {
        return array(
            array(
                'contact_time,clienteleid, prsonid,type',
                'required'
            ) ,
            array(
                'stage, status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                ' add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'type',
                'length',
                'max' => 15
            ) ,
            array(
                'next_subject, accessory, note',
                'length',
                'max' => 255
            ) ,
            
            array(
                'itemid, fromid, manageid, clienteleid, prsonid, type, stage, contact_time, next_contact_time, next_subject, accessory, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note, status',
                'safe',
                'on' => 'search'
            ) ,
            
            array(
                'next_contact_time,contact_time',
                'checkTime'
            ) ,
        );
    }
    
    public function relations() {
        
        return array(
            'iClientele' => array(
                self::BELONGS_TO,
                'Clientele',
                'clienteleid',
                'condition' => '',
                'order' => ''
                /*,'on'=>'iClientele.itemid=clienteleid'*/
            ) ,
            'iContactpPrson' => array(
                self::BELONGS_TO,
                'ContactpPrson',
                'prsonid',
                'condition' => '',
                'order' => ''
                /* ,'on'=>'prsonid=iContactpPrson.itemid'*/
            ) ,
        );
    }
    
    public function attributeLabels() {
        $arr = array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'manageid' => '会员ID',
            'clienteleid' => '客户',
            'prsonid' => '联系人',
            'type' => '类型', /*(电话,电子邮件,上门,邮寄,短信,其他)*/
            'stage' => '销售阶段', /*(1：初期沟通,2:立项评估,3:需求分析,4:方案制定,5:招投标,6:商务谈判,7:合同签订,8:得单,9:失单)*/
            'contact_time' => '联系时间',
            'next_contact_time' => '下次联系时间',
            'next_subject' => '下次议题',
            'accessory' => '附件',
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
            'status' => '状态', /*(0:回收站,1:正常)*/
            'iContactpPrson.nicename' => '联系人',
            'iClientele.clientele_name' => '客户',
        );
        $_arr = parent::attributeLabels();
        if (is_array($_arr) && count($_arr) > 0) {
            foreach ($_arr as $key => $value) {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
    
    public function search() {
        
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('manageid', $this->manageid);
        $criteria->compare('clienteleid', $this->clienteleid);
        $criteria->compare('prsonid', $this->prsonid);
        
        if ($this->type >= 0) {
            $criteria->compare('type', $this->type, true);
        }
        if ($this->stage >= 0) {
            $criteria->compare('stage', $this->stage, true);
        }
        
        $this->setCriteriaTime($criteria, array(
            'contact_time',
            'next_contact_time'
        ));
        $criteria->compare('next_subject', $this->next_subject, true);
        $criteria->compare('accessory', $this->accessory, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('status', $this->status);
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
        $arr['order'] = 'contact_time DESC';
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    public function scopes() {
        $result = parent::scopes();
        $result['listnp'] = array(
            'condition' => 'clienteleid=' . $this->clienteleid,
        );
        return $result;
    }
    public function group() {
        // SELECT * FROM (SELECT * FROM posts ORDER BY dateline DESC) AS NEW GROUP BY tid ORDER BY dateline DESC LIMIT 10
        
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'itemid in(SELECT a.itemid FROM (SELECT b.itemid,b.clienteleid FROM {{contact}} AS b ORDER BY b.contact_time DESC) AS a GROUP BY a.clienteleid)',
            'order' => 'contact_time DESC',
        ));
        return $this;
    }
    protected function beforeValidate() {
        return parent::beforeValidate();
    }
    
    public function getHtmlLink($name = false, $itemid = false, array $htmlOptions = array() , $action = 'view') {
        if (!$name) {
            $name = $this->iContactpPrson->nicename;
        }
        $link = CHtml::link($name, $this->getLink($itemid));
        return $link;
    }
    //保存数据后
    protected function afterSave() {
        parent::afterSave();
        //插入到行程中
        
        if ($this->next_contact_time > 0) {
            $event = new Events;
            $event->deleteByPk($this->itemid);
            $event->itemid = $this->itemid;
            $event->subject = ' 联系客户 - ' . $this->iContactpPrson->nicename;
            $event->start_time = $this->next_contact_time;
            $event->url = $this->getLink();
            $event->type = $this->type;
            $event->note = $this->next_subject;
            $event->save();
        }
        
        $c = new CDbCriteria;
        $c->condition = 'last_time<' . $this->contact_time;
        // 更新联系人最后联系时间
        ContactpPrson::model()->updateByPk($this->prsonid, array(
            'last_time' => $this->contact_time
        ) , $c);
        // 更新客户最后联系时间
        Clientele::model()->updateByPk($this->clienteleid, array(
            'last_time' => $this->contact_time
        ) , $c);
        // contact_time
        
        
    }
    
    public function del() {
        parent::del();
        Events::model()->deleteByPk($this->itemid);
    }
    
    protected function _getnp($isid = true, $top = 1) {
        return parent::getNP($isid, $top);
    }
    
    public function getNP($isid = true, $top = 1) {
        $c = $this->scopes();
        $arr = array(
            $c['listnp']['condition']
        );
        if ($this->scondition) {
            $arr[] = $this->scondition;
        }
        $this->scondition = implode(' AND ', $arr);
        $result = parent::getNP($isid, $top);
        return $result;
    }
}
