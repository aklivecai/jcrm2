<?php
class TakFile extends DbRecod {
    public static $table = '{{files}}';
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'file_name, file_signature',
                'required'
            ) ,
            array(
                'file_type, parent_file_id, suffix, status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'itemid, olid, manageid, add_us, modified_us',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, version_id, add_time, add_ip, modified_time, modified_ip',
                'length',
                'max' => 10
            ) ,
            array(
                'file_name, file_signature, note',
                'length',
                'max' => 255
            ) ,
            array(
                'file_path',
                'length',
                'max' => 500
            ) ,
            array(
                'file_size, mime_type',
                'length',
                'max' => 64
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, olid, fromid, manageid, file_name, file_type, parent_file_id, version_id, file_path, file_size, file_signature, mime_type, suffix, status, add_time, add_us, add_ip, modified_time, modified_us, modified_ip, note',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'olid' => '历史编号', /*(如有则不显示)*/
            'fromid' => '会员ID',
            'manageid' => '会员编号',
            'file_name' => '文件名',
            'file_type' => '文件类型', /*(1:文件夹，0:文件)*/
            'parent_file_id' => '目录编号', /*(可以是模块编号10-1000,10联系记录)*/
            'version_id' => '文件当前版本ID', /*(累加,模块的话就是模块信息的编号)*/
            'file_path' => '路径', /*(/用户ID/文件夹名字/name.jpg)*/
            'file_size' => '文件大小',
            'file_signature' => '文件hash值',
            'mime_type' => '互联网媒体类型', /*(mime type)*/
            'suffix' => '文件后缀', /*(1-99:文档文件,100-199:图片文件,200-299:压缩包文件,300-399:多媒体文件,400+:其他文件)*/
            'status' => '状态', /*(0:回收站,1:正常)*/
            'add_time' => '添加时间',
            'add_us' => '添加人',
            'add_ip' => '添加IP',
            'modified_time' => '修改时间',
            'modified_us' => '修改人',
            'modified_ip' => '修改IP',
            'note' => '备注',
        );
    }
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
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
    /**
     * 获取文件信息,大小名字,上传时间,上传人
     * @return [type] [description]
     */
    public function getInfo() {
        $uname = Manage::getNameById($this->manageid);
        $data = array(
            'itemid' => Ak::setSId($this->primaryKey) ,
            'manageid' => Ak::setSId($this->manageid) ,
            'time' => Ak::timetodate($this->add_time, 6) ,
            'name' => $this->file_name,
            'user' => $uname,
            'note' => $this->note,
        );
        //type image
        if ($this->suffix == 100) {
            $data['src'] = Yii::app()->params['mainSite'] . $this->file_path;
        }
        return $data;
    }
    /**
     * 更新文件归属,一般用于第一次添加信息
     * @param  array $ids        字段ID列表
     * @param  int $version_id 信息编号
     * @return [type]             [description]
     */
    public function upVId($ids, $version_id, $parent_file_id) {
        $sqlWhere = ' WHERE fromid=:fromid  AND version_id=0 AND parent_file_id=:parent_file_id  AND itemid IN(:ids) ';
        $sql = "SELECT itemid FROM :table " . $sqlWhere;
        $ids[] = 0;
        $data = array(
            ':table' => self::$table,
            ':fromid' => Ak::getFormid() ,
            ':version_id' => $version_id,
            ':parent_file_id' => $parent_file_id,
            ':ids' => implode(',', $ids) ,
        );
        $sql = strtr($sql, $data);
        $tags = self::$db->createCommand($sql)->queryColumn();
        $ids = array();
        foreach ($tags as $key => $value) {
            $ids[] = $value;
        }
        
        if (count($ids) > 0) {
            $data[':ids'] = implode(',', $ids);
            $sql = "UPDATE  :table SET version_id=:version_id" . $sqlWhere;
            $sql = strtr($sql, $data);
            $tags = self::$db->createCommand($sql)->query();
        }
        return $ids;
    }
};
