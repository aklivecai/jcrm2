<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {
    public $username;
    public $password;
    public $rememberMe = false;
    public $fromid;
    
    private $_identity;
    
    public function __construct() {
        parent::__construct();
    }
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array(
                'username, password,fromid',
                'required'
            ) ,
            // rememberMe needs to be a boolean
            array(
                'rememberMe',
                'boolean'
            ) ,
            array(
                'fromid',
                'numerical',
                'integerOnly' => true
            ) ,
            // password needs to be authenticated
            array(
                'fromid',
                'fromiDecode'
            ) ,
            array(
                'password',
                'authenticate'
            ) ,
        );
    }
    
    public function fromiDecode($attribute, $params) {
        if (!$this->fromid) {
            $this->addError('fromid', '非法操作！！');
        }
    }
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '登录帐号',
            'password' => '登录密码',
            'rememberMe' => '保存密码',
            'fromid' => '企业编号',
        );
    }
    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
         if ($this->password==''||$this->username=='') {
            return false;
        }
        $this->_identity = new UserIdentity($this->fromid, $this->username, $this->password);
        if (!$this->_identity->authenticate()) {
            $key = 'username';
            switch ($this->_identity->errorCode) {
                case 2:
                    $str = '密码错误！';
                    $key = 'password';
                break;
                case 1:
                    $str = '不存在用户！';
                break;
                case 8:
                    $str = '帐号禁止登录！';
                break;
                case 9:
                    $str = '帐号还没有激活,请激活后使用！';
                break;
                default:
                break;
            }
            if ($this->username != '') {
                $arr = array(
                    'fromid' => $this->fromid,
                    'user_name' => $this->username
                );
                AdminLog::log($str, $arr);
            }
            $this->addError($key, $str);
        }
    }
    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->fromid, $this->username, $this->password);
            $this->_identity->authenticate();
        }
        // Tak::KD($this->_identity->errorCode);
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
        //记住密码，没有则为０,有则保存账户登录状态30天
            $duration = $this->rememberMe ? 3600 * 24 * 1 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            // 更新登录次数，信息
            Manage::model()->upLogin();
            $ck = new CHttpCookie('fid', Tak::setCryptNum($this->fromid));
            $ck->expire = time() + 3600000;
            Yii::app()->request->cookies['fid'] = $ck;
            return true;
        } else return false;
    }
}
