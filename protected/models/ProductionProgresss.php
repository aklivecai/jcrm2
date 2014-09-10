<?php
/**
 * 这个模块来自表 "{{production_progresss}}".
 *
 * 数据表的字段 '{{production_progresss}}':
 * @property string $itemid
 * @property string $fromid
 * @property string $manageid
 * @property string $process_id
 * @property string $add_time
 * @property integer $progress
 */
class ProductionProgresss extends DbRecod {
    public static $table = '{{production_progresss}}';
    public function rules() {
        return array(
            array(
                'production_id,process_id,progress',
                'required'
            ) ,
            array(
                'status',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'production_id,itemid, manageid, process_id',
                'length',
                'max' => 25
            ) ,
            array(
                'fromid, add_time',
                'length',
                'max' => 10
            ) ,
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'itemid,  process_id, add_time, progress,status',
                'safe',
                'on' => 'search'
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'itemid' => '编号',
            'fromid' => '平台会员ID',
            'manageid' => '会员ID',
            'process_id' => '工时编号',
            'add_time' => '添加时间',
            'progress' => '进度',
            'status' => '状态',
        );
    }
    
    public function search() {
        $cActive = parent::search();
        $criteria = $cActive->criteria;
        $criteria->compare('itemid', $this->itemid);
        $criteria->compare('fromid', $this->fromid);
        $criteria->compare('manageid', $this->manageid, true);
        $criteria->compare('process_id', $this->process_id, true);
        $criteria->compare('add_time', $this->add_time, true);
        $criteria->compare('progress', $this->progress);
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
        if ($this->isNewRecord) {
            $arr = Tak::getOM();
            $this->itemid = $arr['itemid'];
            $this->fromid = $arr['fromid'];
            $this->add_time = $arr['time'];
            $this->manageid = $arr['manageid'];
        }
        return true;
    }
    //保存数据后
    protected function afterSave() {
        if ($this->status == 2) {
            $this->upStatus();
        } else {
            $p = Production::model()->findByPk($this->production_id);
            //排期完成，开始生产，没有生产记录则更新生产单状态为生产状态
            if ($p->status == 2) {
                $p->upStatus(3);
            }
        }
    }
    //删除信息后
    protected function afterDelete() {
        parent::afterDelete();
    }
    /**
     * 同步修改，工序用时进度为完成，更新车间进度，确认是否都完成
     * @return [type] [description]
     */
    private function upStatus() {
        $sql = " UPDATE :Production_Days SET progress=1 WHERE fromid=:fromid  AND itemid=:process_id ";
        $data = array(
            ':Production_Days' => ProductionDays::$table,
            ':fromid' => Ak::getFormid() ,
            ':process_id' => $this->process_id,
            ':production_id' => $this->production_id,
        );
        $sql = strtr($sql, $data);
        // Tak::KD($sql, 1);
        $result = self::$db->createCommand($sql)->execute();
        if ($result) {
            $p = Production::model()->findByPk($this->production_id);
            if ($p->status == 2) {
                $p->upStatus(3);
            } else {
                //判断是否所有的工序都已经完成，更新生产状态为已完成（生产工序总数＝＝汇总后的总进度数）
                $sql = "SELECT COUNT(1) AS col1,SUM(progress) AS col2,production_id FROM :Production_Days WHERE fromid=:fromid AND production_id=:production_id";
                $sql = strtr($sql, $data);
                $result = self::$db->createCommand($sql)->queryRow();
                if ($result['col1'] > 0 && $result['col1'] - $result['col2'] == 0) {
                    // Tak::KD($sql);
                    $p->StatusOver();
                }
            }
        }
        return $result;
    }
    /**
     * 根据工序的编号查询进度，按照最新的排在前面
     * @param  int $process_id 工序的编号
     * @return array             [description]
     */
    public static function getListByProcessid($process_id) {
        $sql = 'SELECT *  FROM :table WHERE fromid=:fromid AND process_id=:process_id ORDER BY add_time ASC';
        $data = array(
            ':table' => self::$table,
            ':fromid' => Ak::getFormid() ,
            ':process_id' => $process_id,
        );
        $sql = strtr($sql, $data);
        // Tak::KD($sql, 1);
        $result = Ak::getDb('db')->createCommand($sql)->queryAll(true);
        return $result;
    }
}
