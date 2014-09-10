<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	public $fromid;

	const ERROR_NOT = 8;

	public function __construct($fromid,$username,$password)
	{
		parent::__construct($username,$password);
		$this->fromid = $fromid;
	}
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		Ak::getWdb($this->fromid);
		Ak::db(false,$this->fromid);
		$user = Manage::model()->find('LOWER(user_name)=:username AND fromid=:fromid '
			,array(
				':username' => strtolower($this->username)
				,':fromid' => $this->fromid
			)
		);
		if($user===null){
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		else if(!$user->validatePassword($this->password)){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			if ($user->user_status==0) {
				// 禁止登录
				$this->errorCode = self::ERROR_NOT;
			}else{
				$this->_id=$user->manageid;
				$this->username = $user->user_name;
				//记录平台会员编号
				$this->setState('last_login_time', Tak::timetodate($user->last_login_time,6));
				$this->setState('fromid', $user->fromid);

				$this->setState('branch', $user->branch);
				$this->setState('isbranch', $user->isbranch==1);
				$this->errorCode = self::ERROR_NONE;
			}
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}