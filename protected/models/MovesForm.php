<?php
class MovesForm extends CFormModel {
    
    public $fMid;
    public $tMid;
    public $note;
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'note',
                'length',
                'max' => 255
            ) ,
            array(
                'fMid,tMid',
                'required'
            ) ,
            array(
                'fMid,tMid',
                'numerical',
                'integerOnly' => true
            ) ,
            array(
                'fMid,tMid',
                'checkMid'
            ) ,
            array(
                'tMid',
                'checkRa'
            ) ,
        );
    }
    public function checkRa($attribute, $params) {
        if ($this->tMid != '' && $this->tMid == $this->fMid) {
            $this->addError($attribute, '一样的用户不允许转移');
        }
    }
    public function checkMid($attribute, $params) {
        $v = $this->$attribute;
        if ($v) {
            $result = Manage::model()->findByPk($v);
            if ($result === null) {
                $this->addError($attribute, '不存在此用户');
            }
        }
    }
    public function attributeLabels() {
        return array(
            'fMid' => '来源用户',
            'tMid' => '转移用户',
            'note' => '备注',
        );
    }
    
    public function moveClienteles($clienteleid = false) {
        $arr = array(
            ':fmid' => $this->fMid,
            ':tmid' => $this->tMid,
            
            ':c' => '{{clientele}}',
            ':cp' => '{{contactp_prson}}',
            ':cc' => '{{contact}}',
            ':ip' => Ak::getIps() ,
        );
        $sqls = array();
        $result = array(
            ':note' => $this->note
        );
        $sqlXXXX = '
		UPDATE :c c, :cp cp ,:cc cc
			SET c.manageid = :tmid
				,cp.manageid = :tmid
				,cc.manageid = :tmid
			WHERE c.manageid = :fmid AND c.itemid=cp.clienteleid AND
			 c.itemid = cc.clienteleid
		 ';
        $connection = Ak::db(true);
        $transaction = $connection->beginTransaction();
        foreach (array(
            'c',
            'cp',
            'cc'
        ) as $key => $value) {
            //同时更新IP方便以后还原,所以不修改时间是因为可能会会动用到客户的信息
            $_sql = "UPDATE :$value SET manageid = :tmid,modified_ip=:ip WHERE manageid = :fmid ";
            if ($clienteleid) {
                $_sql.= ' AND ';
                $_sql.= $key == 0 ? 'itemid' : 'clienteleid';
                $_sql.= "=$clienteleid";
            }
            $_sql.= ';';
            $sqls[$value] = $_sql;
        }
        try {
            foreach ($sqls as $key => $value) {
                $sql = strtr($value, $arr);
                // Tak::KD($sql);
                $temp = $connection->createCommand($sql)->execute();
                $result[$key] = $temp;
            }
            // Tak::KD($result,1);
            
            $str = '成功转移 客户 <span class="red">:c</span> , 联系人<span class="red">:cp</span>,  联系记录<span class="red">:cc</span><hr />备注：:note';
            $str = strtr($str, $result);
            AdminLog::log(Tk::g(array(
                'Clienteles',
                'Move'
            )) . ' <hr /> ' . $str);
        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $transaction->rollBack();
            return false;
        }
        return $result;
    }
}
