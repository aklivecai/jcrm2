<?php
class SearchWageForm extends CFormModel {
    public $keyword = '';
    public $yea = 0;
    private $yea_v = 0;
    public function rules() {
        return array(
            array(
                'keyword',
                'length',
                'max' => 200
            ) ,
            array(
                'yea',
                'length',
                'max' => 32,
            ) ,
            
            array(
                'keyword',
                'checkSql'
            ) ,
            array(
                'keyword,yea',
                'safe',
            ) ,

        );
    }
    public function checkSql($attribute, $params) {
        Tak::KD($this->$attribute,1);
        if ($this->$attribute) {
            $this->$attribute = addslashes($this->$attribute);
        }
    }
    public function attributeLabels() {
        return array(
            'yea' => '年份',
            'keyword' => '名字',
        );
    }
    
    public function getYea_v() {
        $rs = $this->getYeas();
        if (isset($rs[$this->yea])) {
            $result = $rs[$this->yea];
        } else {
            $result = date('Y');
        }
        return $result;
    }
    
    public $yeas = null;
    public function getYeas() {
        if ($this->yeas == null) {
            $yea = date('Y');
            $listY = array();
            $start = $yea - 8;
            $fid = Tak::getFormid();
            for ($i = $yea;$i > $start;$i--) {
                $listY[md5($i . $fid) ] = $i;
            }
            $this->yeas = $listY;
            $this->yea = md5($yea . $fid);
        }
        return $this->yeas;
    }
}
