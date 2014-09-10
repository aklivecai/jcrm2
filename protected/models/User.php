<?php

class User extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $salt
	 * @var string $email
	 * @var string $profile
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, salt, email', 'required'),
			array('username, password, salt, email', 'length', 'max'=>128),
			array('profile', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Username',
			'password' => 'Password',
			'salt' => 'Salt',
			'email' => 'Email',
			'profile' => 'Profile',
		);
	}

    public function beforeSave()
    {
        if ( $this->isNewRecord )
        {
            // $this->status = 0;
            // $this->activkey = $this->GenerateActivkey();
            $this->salt = $this->GenerateSalt();
            // $this->create_at = date( 'Y-m-d H:i:s', time() );
            //加密密码
            $this->password = $this->hashPassword($this->password, $this->salt);
        }

        return parent::beforeSave();
    }	

    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password)
    {
        return $this->hashPassword($password,$this->salt)===$this->password;
    }

    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password,$salt)
    {
        return md5($salt.$password);
    }

    /**
     * 生成一个激活Key
     * @return string
     */
    public function generateActivkey()
    {
        return md5(uniqid($this->username.$this->password, true));
    }

    /**
     * 生成一个SALT码
     */
    public function generateSalt()
    {
        $seed = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";   //   输出字符集
        for( $i=0; $i<5; $i++)
            $seed = str_shuffle($seed);

        $salt = substr( $seed , 0, 4 );
        return  $salt;
    }
}