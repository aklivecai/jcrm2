<?php

class PasswdModifyForm extends CFormModel
{
    public $oldPasswd;
    public $passwd;
    public $passwdConfirm;
    public function rules()
    {
        return array(
        array('oldPasswd,passwd,passwdConfirm', 'required'),
        array('passwd', 'length', 'min'=>6, 'max'=>64),
        array('passwdConfirm', 'compare', 'compareAttribute' => 'passwd'),
        array('oldPasswd', 'validOldPasswd'),
        array('passwd', 'checkPassword'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'oldPasswd'     => "当前密码&nbsp;",
            'passwd'        => "新密码&nbsp;",
            'passwdConfirm' => "确认密码&nbsp;",
        );
    }
    
    public function validOldPasswd(){
        if($this->hasErrors()==false){
            $id = Tak::getManageid();
            $user = Manage::model()->findByPk($id);
            if($user->validatePassword($this->oldPasswd)){
                return true;
            }else{
                $this->addError('oldPasswd',"当前密码输入错误");
            }
        }
        return false;
    }

    
    public function checkPassword(){
        if(!$this->hasErrors())
        {
            $strArr = explode(" ", $this->passwd);
            if(count($strArr)!=1){
                $this->addError('passwd',"密码中不能有空格");
                return;
            }
        }
    }

    public function save(){
        if($this->validate()){
            $id = Tak::getManageid();
            $user = Manage::model()->findByPk($id);

            $user->user_pass = $this->passwd;
            if($user->save()){
                AdminLog::log('修改密码');
            }else{
                $arr = $user->getErrors();
                foreach ($arr as $key => $value) {
                    $this->addError('',$value[0]);
                }
                return false;
            }
            // User::model()->modifyPasswordToken($id);
            // $userIdentity = new UserIdentity(NULL, NULL);
            // $userIdentity->updateCookies('_uidp', md5($user["user_pass"]. $user["salt"]));
            return true;
        }
        return false;
    }
}