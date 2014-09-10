<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SearchForm extends CFormModel
{
	public $keyword = '';
	public $catid = 0;

	public function rules()
	{
		return array(
			// username and password are required
			 // array('keyword', 'required'),
			 array('keyword', 'length', 'max'=>255),
			 array('catid', 'numerical', 'integerOnly'=>true),
		);
	}
	public function attributeLabels()
	{
		return array(
			'keyword'=>'关键字',
			'catid'=>'分类',
		);
	}
}
