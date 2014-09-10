<?php
/**
 * 表单提交数据用于生成表单字段
 */
class FormProducer {
    private $_fields = array();
    private $_values = array();
    public function __construct() {
    }
    /**
     * 将字段数据插入数据库
     * @param  [type] $field_model [字段模型数组]
     * @return [type] bool
     */
    public function insertField($fields = array() , $form_id = 0) {
        $r = true;
        $db_con = Yii::app()->db;
        $transaction = $db_con->beginTransaction();
        try {
            $allField = FormField::model()->findAll(array(
                "condition" => "form_id = :form_id",
                "params" => array(
                    ":form_id" => $form_id
                ) ,
                "order" => "field_id desc",
            ));
            if (count($allField) > 0) $inname = $allField[0]->inname;
            else $inname = 'field0';
            $inname_n = intval(substr($inname, 5)) + 1;
            foreach ($fields as $field) {
                $model = new FormField;
                $model->form_id = $form_id;
                $model->field_name = $field['field_name'];
                $model->field_type = $field['field_type'];
                $model->field_value = $field['field_value'];
                $model->field_default = $field['field_default'];
                $model->field_desc = $field['field_desc'];
                $model->inname = 'field' . $inname_n;
                if (!$model->save()) {
                    $r = false;
                    throw new Exception("Error Processing Request", 1);
                    break;
                }
                ++$inname_n;
            }
            $transaction->commit();
        }
        catch(Exception $e) {
            $transaction->rollback();
            $r = false;
        }
        return $r;
    }
    /**
     * 将FormField模型的字段属性进行存储,多维数组
     * @param  array  FormField []
     * @return [type]         [description]
     */
    public function pushField($fields = array()) {
        foreach ($fields as $field) {
            $this->_fields[] = $field->attributes;
        }
    }
    /**
     * 将FormField模型的值进行存储,多维数组
     * @param  array  FormField []
     * @return [type]         [description]
     */
    public function pushValue($values = array()) {
        foreach ($values as $value) {
            $this->_values[$value->field_name] = $value->attributes;
        }
    }
    /**
     * 根据字段属性,查找类文件将字段解析为html
     * @return [array]  所有字段解析为html的数组
     */
    public function parseField() {
        $htmls = array();
        foreach ($this->_fields as $field) {
            $class = ucfirst($field['field_type']) . 'Field';
            if (class_exists($class = ucfirst($field['field_type']) . 'Field')) {
                $field = new $class($field);
                $htmls[] = $field->show();
            }
        }
        return $htmls;
    }
    //构建表单html,生成表单字段
    public function buildForm($form = stdClass) {
        $htmls = $this->parseField();
        $i = 0;
        $formhtml = '';
        if (count($htmls) > 0) {
            $formhtml = '<div class="well"><table><caption>' . $form->form_name . '</caption>';
            foreach ($htmls as $fieldHtml) {
                if ($i % 2 == 0) {
                    $formhtml.= '<tr><td>' . $fieldHtml . '</td>';
                } else {
                    $formhtml.= '<td>' . $fieldHtml . '</td></tr>';
                }
                ++$i;
            }
            $formhtml.= '</table></div>';
        }
        return $formhtml;
    }
    /**
     * 以非表单形式展示字段及其值
     * @return [type] [description]
     */
    public function buildHtml() {
        $html = '<table>';
        $i = 0;
        foreach ($this->_fields as $field) {
            if (array_key_exists($field['inname'], $this->_values)) {
                $value = $this->_values[$field['inname']]['value'];
                if ($field['field_type'] == 'select') {
                    $v_option = explode("\n", $field['field_value']);
                    $value = $v_option[$this->_values[$field['inname']]['value']];
                }
                if ($i % 2 == 0) {
                    $html.= '<tr><td><span>' . $field['field_name'] . ':</span><span><input disabled="disabled" value="' . $value . '"/></span>' . $field['field_desc'] . '</td>';
                } else {
                    $html.= '<td><span>' . $field['field_name'] . ':</span><span><input disabled="disabled" value="' . $value . '"/></span>' . $field['field_desc'] . '</td></tr>';
                }
                ++$i;
            }
        }
        $html.= '</table>';
        return $html;
    }
}
