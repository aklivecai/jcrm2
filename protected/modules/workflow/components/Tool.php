<?php
class Tool {
    public static $_users = null;
    /**
     * 用于将时间戳格式化为需要格式
     * @param  [type] $time [description]
     * @return [type]       [description]
     */
    public static function dateFormat($time) {
        return date('Y-m-d', $time);
    }
    /**
     * 生成模拟用户数组
     * @return [type] [description]
     */
    public static function userList() {
        if (self::$_users == null) {
            self::$_users = array(
                '1' => '用户',
                '10' => '人事(行政部门)',
                '20' => '设计师',
                '2' => '客户',
                /*
                '30' => '销售',
                '40' => '评审',
                '50' => '营销总监',
                '60' => '副总',
                '70' => '总经理',
                */
                '80' => '管理员',
            );
        }
        return self::$_users;
    }
    /**
     * 通过用户id,获取用户显示名称
     * @return [type] [description]
     */
    public static function getUname($name) {
        $users = self::userList();
        return isset($users[$name]) ? $users[$name] : '未知';
    }
    /**
     * 生成用户列表的option,用于js
     * @param  string $u [description]
     * @return [type]    [description]
     */
    public static function userListJs($u = '') {
        $users = self::userList();
        $option = '';
        foreach ($users as $user => $label) {
            if ($user == $u) $option.= "<option value='" . $user . "' selected='selected'>" . $label . "</option>";
            else $option.= "<option value='" . $user . "'>" . $label . "</option>";
        }
        return $option;
    }
    /**
     * 判断是否为管理员
     * @return [type] [description]
     */
    public static function isadmin() {
        $userInfo = Yii::app()->session->get('userInfo');
        return $userInfo['name'] == '80';
    }
    /**
     * 获取所有步骤信息
     * @param  string $flow_id [description]
     * @return [type]          [description]
     */
    public static function getAllstep($flow_id = '') {
        $steps = array();
        $stepModels = FlowStep::model()->findAll('flow_id=:flow_id', array(
            ':flow_id' => $flow_id
        ));
        foreach ($stepModels as $model) {
            $steps[$model->step_id] = $model->step_name;
        }
        return $steps;
    }
    /**
     * 根据步骤id,获取步骤名称
     * @param  [type] $step_id [description]
     * @return [type]          [description]
     */
    public static function getStepname($step_id) {
        $model = FlowStep::model()->findByPk($step_id);
        if ($model) return $model->step_name;
        else return null;
    }
    /**
     * 获取所有表单信息
     * @return [type] [description]
     */
    public static function getAllform() {
        $forms = array();
        $models = FormInfo::model()->findAll();
        foreach ($models as $model) {
            $forms[$model->form_id] = $model->form_name;
        }
        return $forms;
    }
    /**
     * 根据表单id,获取表单中所有字段信息
     * @param  [type] $form_id [description]
     * @return [type]          [description]
     */
    public static function getFormField($form_id) {
        $fields = array();
        $models = FormField::model()->findAll('form_id=:form_id', array(
            'form_id' => $form_id
        ));
        foreach ($models as $model) {
            $fields[$model->field_id] = $model->field_name;
        }
        return $fields;
    }
    /**
     * 根据字段id,获取字段名称
     * @param  [type] $step_id [description]
     * @return [type]          [description]
     */
    public static function getFieldName($step_id) {
        $fieldModl = FormField::model()->findByPk($step_id);
        return $fieldModl->field_name;
    }
    /**
     * 设置步骤条件的对应关系
     * @return [type] [description]
     */
    public static function getConditionType() {
        return array(
            'eq' => '等于',
            'gt' => '大于',
            'lt' => '小于',
        );
    }
    public static function getFieldTypes($key = false) {
        $list = array(
            'text' => '文本框',
            'textarea' => '多行文本框',
            'select' => '下拉列表框',
            'radio' => '单选按钮',
            'checkbox' => '多选框',
        );
        
        if ($key && isset($list[$key])) {
            return $list[$key];
        }
        return $list;
    }
}
?>