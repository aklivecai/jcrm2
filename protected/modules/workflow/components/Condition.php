<?php
/**
 * 处理步骤条件类
 */
class Condition
{
	private $_selectFieldInfo = array(); //保存下拉列表字段类型信息
	public function __construct()
	{}

	/**
	 * 根据步骤模型,获取对应步骤的所有条件
	 * @param  [type] $stepModl [description]
	 * @return [type]           [description]
	 */
	public function getConditon($stepModl)
	{
		$conditions = array();
		if(!is_object($stepModl))
		{
			return false;
		}
		$step_id = $stepModl->step_id;
		$allCon = FlowCond::model()->findAll('step_id=:step_id', array('step_id'=>$step_id));
		foreach ($allCon as $con) 
		{
			$conditions[] = array('field_id'=>$con->field_id,'type'=>$con->type,'value'=>$con->value);
		}
		return $conditions;
	}

	/**
	 * 获取该流程中的所有表单值
	 * [getValue description]
	 * @return [type] [description]
	 */
	public function getValue($conditions = array())
	{
		$field_ids = array();
		$fieldValue = array();
		foreach ($conditions as $condition) {
			$field_ids[] = $condition['field_id'];
		}
		$criteria = new CDbCriteria;
		$criteria->addInCondition('field_id',$field_ids);
		$fieldValueModels = FormValue::model()->findAll($criteria);
		//获取表单所有字段的值
		foreach ($fieldValueModels as $valueModel) {
			$fieldValue[$valueModel->field_id] = $valueModel->value;
		}
		// 获取表单所有下拉列表字段
		$criteria->addCondition('field_type = "select"', 'and');
		$fieldInfoModels = FormField::model()->findAll($criteria);
		foreach ($fieldInfoModels as $fieldInfoModel) {
			$option = explode("\n", $fieldInfoModel->field_value);
			$this->_selectFieldInfo[$fieldInfoModel->field_id] = $option;
		}

		return $fieldValue;
	}

	/**
	 * 判断是否符合条件
	 * @return [type] [description]
	 */
	public function decide($conditions, $fieldValue)
	{
		$pass = true;
		//遍历所有条件,并判断。所以所有条件关系为AND.
		foreach ($conditions as $condition) 
		{
			$type = $this->getConType($condition['type']);
			$con_value = $condition['value'];
			$value = $fieldValue[$condition['field_id']];
			if(isset($this->_selectFieldInfo[$condition['field_id']]))
			{
				$selectField = $this->_selectFieldInfo[$condition['field_id']];
				$value = $selectField[$value];
			}
			//下拉列表换行问题
			//拼接判断表达式,并执行
			$code = "'".trim($value)."'".$type."'".$con_value."'";
			var_dump($code);
			$pass = eval('return '.$code.';');
			if(!$pass)
			{
				break;
			}
		}
		return $pass;
	}

	/**
	 * 获取条件类型表达式
	 * @param  [type] $index [description]
	 * @return [type]        [description]
	 */
	public function getConType($index)
	{
		$type = array('gt'=>'>','lt'=>'<','eq'=>'==');
		// $type = Tool::getConditionType();
		return $type[$index];
	}

	/**
	 *执行条件判断
	 * @param  [type] $stepModl [description]
	 * @return [type]           [description]
	 */
	public function runDecide($stepModl)
	{
		$pass = '';
		if(!$stepModl)
			return false;
			// throw new Exception("Error Processing Request", 1);
		$conditions = $this->getConditon($stepModl);
		$fieldValue = $this->getValue($conditions);
		$pass = $this->decide($conditions,$fieldValue);
		return $pass;
	}
}
?>