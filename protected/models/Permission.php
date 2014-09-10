<?php
class Permission extends CActiveRecord {
    public $name;
    public $description;
    public $type = 2;
    public $bizRule = '';
    public $data = null;
    
    public $fromid;
    
    public function tableName() {
        return '{{rbac_authitem}}';
    }
    private function getFid() {
        return Tak::getFormid();
    }
    public function gettitle() {
        return $this->description;
    }
    public function primaryKey() {
        return 'name';
    }
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function rules() {
        return array(
            array(
                'description',
                'required'
            ) ,
            array(
                'description',
                'checkRepetition'
            ) ,
        );
    }
    /**
     * 检验重复
     */
    public function checkRepetition($attribute, $params) {
        $sql = array(
            "LOWER(:col)=:val AND fromid=':fromid' "
        );
        $arr = array(
            ':col' => $attribute,
            ':fromid' => $this->getFid() ,
        );
        if ($this->name <> '') {
            $sql[] = ':ikey<>:itemid';
            $arr[':ikey'] = 'name';
            $arr[':itemid'] = $this->name;
        }
        $sql = implode(' AND ', $sql);
        $sql = strtr($sql, $arr);
        $m = $this->find($sql, array(
            ':val' => strtolower($this->$attribute)
        ));
        
        if ($m != null) {
            $err = $this->getAttributeLabel($attribute) . ' 已经存在 :';
            $this->addError($attribute, $err);
        }
    }
    public function attributeLabels() {
        return array(
            'name' => "部门",
            'description' => "部门",
            'bizRule' => "业务规则",
            'data' => "数据",
        );
    }
    public function defaultScope() {
        $arr = array();
        return $arr;
    }
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('fromid', $this->getFid());
        $criteria->compare('description', $this->description, true);
        $criteria->order = ' name ASC';
        $criteria->select = 'name,description';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 5000,
            ) ,
        ));
    }
    
    protected function beforeSave() {
        $result = parent::beforeSave();
        if ($result) {
            if ($this->isNewRecord) {
                $this->name = Tak::fastUuid();
            }
            $this->fromid = $this->getFid();
        }
        return $result;
    }
    // 删除部门,清空部门下的所有权限,部门下的所有人的权限
    protected function afterDelete() {
        parent::afterDelete();
        $sql = "DELETE FROM {{rbac_authitemchild}} WHERE parent=:parent";
        $arr = array(
            ':parent' => $this->name,
        );
        $sql = "DELETE FROM {{rbac_authitemchild}} WHERE parent=:parent";
        self::$db->createCommand($sql)->execute($arr);
        $arr[':fromid'] = $this->getFid();
        $sql = "DELETE FROM {{rbac_authassignment}} WHERE itemname=:parent  AND  fromid=:fromid";
        self::$db->createCommand($sql)->execute($arr);
    }
    
    public static function getList($isload = false) {
        $model = new self('search');
        $tags = $model->search()->getData();
        $reulst = array();
        foreach ($tags as $key => $value) {
            $reulst[$value['name']] = $value['description'];
        }
        if ($isload) {
            $reulst['800'] = '供应商';
        }
        // $reulst['']
        return $reulst;
    }
    
    public function getChild() {
        $arr = array(
            ':parent' => $this->name,
            ':fromid' => $this->fromid,
        );
        $sql = "SELECT count(parent) FROM {{rbac_authitemchild}} WHERE parent=:parent";
        $count = self::$db->createCommand(strtr($sql, $arr))->queryScalar();
        $sql = 'SELECT C.*,P.* FROM {{rbac_authitemchild}} C,{{rbac_authitem}} P WHERE  C.child = P.name AND C.parent=:parent ORDER BY P.type DESC,P.description ASC';
        $dataProvider = new CSqlDataProvider(strtr($sql, $arr) , array(
            'keyField' => 'child',
            'totalItemCount' => $count,
            'sort' => array(
                'attributes' => array(
                    'parent',
                    'child',
                ) ,
            ) ,
            'pagination' => array(
                'pageSize' => 100,
            ) ,
        ));
        return $dataProvider;
    }
    /**
     *获取所有的仓库管理员
     */
    public static function _getUWarehouses() {
        $sql = sprintf("SELECT r.userid,m.user_nicename,m.user_name FROM {{rbac_authassignment}} AS r LEFT JOIN {{manage}} AS m ON(r.userid=m.manageid)  WHERE r.fromid=%s AND r.itemname='Warehouse'", Ak::getFormid());
        $_result = Ak::getDb('db')->createCommand($sql)->queryAll();
        $result = array();
        foreach ($_result as $key => $value) {
            $result[$value['userid']] = $value['user_nicename'] != '' ? $value['user_nicename'] : $value['user_name'];
        }
        return $result;
    }
    /**
     *获取所有的仓库管理员
     */
    public static function getUWarehouses() {
        $fid = Ak::getFormid();
        $sql = sprintf("SELECT r.userid FROM {{rbac_authassignment}} AS r  WHERE r.fromid=%s AND r.itemname='Warehouse' GROUP BY r.userid", $fid);
        $_result = Ak::getDb('db')->createCommand($sql)->queryColumn();
        if (count($_result) > 0) {
            $ids = implode(',', $_result);
            $sql = sprintf("SELECT m.manageid AS userid,m.user_nicename,m.user_name FROM {{manage}} AS m WHERE m.fromid=%s AND m.manageid IN(%s)", $fid, $ids);
            $db = Ak::db(true);
            $_result = $db->createCommand($sql)->queryAll();
        }
        $result = array();
        foreach ($_result as $key => $value) {
            $result[$value['userid']] = $value['user_nicename'] != '' ? $value['user_nicename'] : $value['user_name'];
        }
        return $result;
    }
    /**
     * 查看用户是否是仓库管理员
     * @param  int $uid 管理员编号
     * @return int       返回用户编号
     */
    public static function iSWarehouses($uid = 0) {
        !($uid > 0) && $uid = Ak::getManageid();
        $sql = sprintf("SELECT r.userid FROM {{rbac_authassignment}} AS r WHERE r.fromid=%s AND r.userid=%s AND  r.itemname='Warehouse'", Ak::getFormid() , $uid);
        $_result = Ak::getDb('db')->createCommand($sql)->queryScalar();
        return $_result > 0 ? $uid : 0;
    }
}
