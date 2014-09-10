<?php
/**
 * 这个模块来自表 "{{order_files}}".
 *
 * 数据表的字段 '{{order_files}}':
 * @property string $itemid
 * @property string $action_id
 * @property string $file_type
 * @property string $file_name
 * @property string $file_path
 */
class OrderFiles extends MRecord {
    public static $table = '{{order_files}}';
    /**
     * @return array validation rules for model attributes.字段校验的结果
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'action_id, file_path',
                'required'
            ) ,
            array(
                'itemid, action_id',
                'length',
                'max' => 25
            ) ,
            array(
                'file_type',
                'length',
                'max' => 10
            ) ,
            array(
                'file_name, file_path',
                'length',
                'max' => 255
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid, action_id, file_type, file_name, file_path',
                'safe',
                'on' => 'search'
            ) ,
            
            array(
                'file_type',
                'checkFileType'
            ) ,
        );
    }
    public function checkFileType($attribute = true, $params = true) {
        $result = 0;
        if ($this->file_path != '') {
            $result = Tak::getFileTypeId($this->file_path);
        }
        $this->file_type = $result;
        return $this->file_type;
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
        return array(
            'itemid' => '编号',
            'action_id' => '动作', /*(如订单状态图片,)*/
            'file_type' => '类型', /*(1图片,2压缩包,3是文档)*/
            'file_name' => '文件名',
            'file_path' => '文件路径', /*(/用户ID/文件夹名字/name.jpg)*/
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        
        $criteria->compare('itemid', $this->itemid, true);
        $criteria->compare('action_id', $this->action_id, true);
        $criteria->compare('file_type', $this->file_type, true);
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('file_path', $this->file_path, true);
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
        // $condition[] = 'display>0';
        $arr['condition'] = implode(" AND ", $condition);
        return $arr;
    }
    
    public function scopes() {
        // $posts=Post::model()->published()->recently()->findAll();注意: 命名范围只能用于类级别方法。也就是说，此方法必须使用 ClassName::model() 调用。
        return array(
            'published' => array() ,
            'fileimg' => array(
                'order' => 'file_type ASC',
            ) ,
        );
    }
    //保存数据前
    protected function beforeSave() {
        $result = parent::beforeSave(true);
        if ($result) {
            //添加数据时候
            if ($this->isNewRecord) {
                $arr = Tak::getOM();
                if (!$this->itemid) {
                    $this->itemid = $arr['itemid'];
                }
            } else {
                //修改数据时候
                
                
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
    
    public function getListByActionID($actionid) {
        $arr = array(
            'action_id' => $actionid
        );
        $list = $this->fileimg()->findAllByAttributes($arr);
        return $list;
    }
    
    public function getLink($istitle = false) {
        $result = '';
        $filename = $this->file_name;
        if ($this->file_path != '') {
            $img = $this->file_type == 1 ? $this->file_path : Tak::getFileIco($this->file_type);
            if (!strpos($img, 'ttp://')) {
                // $img = 'http://' . $img;
                // $img = '/' . $img;
            }
            $result = "<img src='$img' width='45' height='45' alt='$filename'/>";
            if ($istitle) {
                $result.= "<span>$filename</span>";
            }
            $result = CHtml::link($result, $this->file_path, array(
                'target' => '_blank'
            ));
        }
        return $result;
    }
}
