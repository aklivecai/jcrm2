<?php
/**
 * 日期类型字段的解析
 */
class DateField implements FieldParseInterface {
    private $_tmp;
    private $_labeltmp;
    public function __construct($option = array()) {
        foreach ($option as $key => $value) {
            $this->$key = $value;
        }
        $this->_tmp = '<div class="inline">';
        $this->_labeltmp = '<div class="inline"><span>{name}</span><span>{value}</span></div>';
    }
    public function setName() {
        $this->_tmp.= CHtml::label($this->field_name, 'for', array());
    }
    
    public function setType() {
        $this->_labeltmp = preg_replace('/\{name\}/', $this->field_name, $this->_labeltmp);
    }
    
    public function setDefault() {
    }
    
    public function setValue() {
        $this->_labeltmp = preg_replace('/\{value\}/', $this->field_name, $this->_labeltmp);
    }
    
    public function show() {
        $this->setName();
        $this->_tmp.= Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
            'language' => 'zh_cn',
            'name' => $this->inname,
            'value' => Date('Y-m-d') ,
            'options' => array(
                'showAnim' => 'fold',
                'showOn' => 'both',
                'buttonImage' => Yii::app()->request->baseUrl . '/images/calendar.gif',
                'maxDate' => '',
                'buttonImageOnly' => true,
                'dateFormat' => 'yy-mm-dd',
            ) ,
            'htmlOptions' => array(
                // 'disabled'=>'disabled',
                'style' => 'height:18px',
                'maxlength' => 8,
            ) ,
        ) , true);
        $this->_tmp.= '</div>';
        return $this->_tmp;
    }
    
    public function showlabel() {
        $this->setName();
        $this->setValue();
        return $this->_labeltmp;
    }
}
?>