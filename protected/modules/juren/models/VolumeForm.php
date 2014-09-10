<?php

class VolumeForm extends CFormModel
{
    public $number = 10;
    public function rules()
    {
        return array(
            array('number', 'required'),
            array('number', 'numerical','min'=>1, 'max'=>500,'integerOnly'=>true),
        );
    }
    public function attributeLabels()
    {
        return array(
            'number'     => "数量",
        );
    }

    public function save(){
        if($this->validate()){
            $arr = Tak::getOM();
            $time = $arr['time'];
            $m = new TestMemeber();
            $m->add_us = $m->manageid = $arr['manageid'];
            $m->add_time = $arr['time'];
            $m->add_ip = $arr['ip'];
            $m->company = '-';
            $m->isLog = false;
            $number = $this->number;
            try
            {
                for (; $number >0 ; $number--) { 
                  $m->isNewRecord = true;
                  if($m->save()){

                  }else{
                    $err = $m->getErrors();
                    foreach ($err as $key => $value) {
                        $this->addError('',$value[0]);
                    }
                  }  
                }                
              
            }
            catch(Exception $e)
            {
               
            } 
            $str = "生成帐号 {$this->number} 个";
            AdminLog::log($str);
            Yii::app()->user->setFlash('msg',$str);
             return $time;
        }
        return false;       
    }
}