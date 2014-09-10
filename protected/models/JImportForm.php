<?php
class JImportForm extends CFormModel {
    public $file = 10;
    public $varfile = null;
    public $model = null;
    public $headers = null;
    public $cols = null;
    
    public $script = null;
    
    public $errors = array();
    public $data = null;
    
    public function getModel() {
        return ucwords($this->model);
    }
    
    public function rules() {
        return array(
            array(
                'file',
                'file',
                'allowEmpty' => true,
                'types' => 'xlsx,xls,et',
                'maxSize' => 1024 * 1024 * 10, // 10MB
                'tooLarge' => '文件最大10M',
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'file' => "文件",
        );
    }
    public function getColName($col, $index) {
        return self::colName($this->model, $col, $index);
    }
    
    public static function colName($model, $col, $index) {
        return sprintf("%s[%s][%s]", $model, $index, $col);
    }
    public function getData($file) {
        $result = false;
        try {
            spl_autoload_unregister(array(
                'YiiBase',
                'autoload'
            ));
            $phpExcelPath = Yii::getPathOfAlias('application.extensions.phpexcel.PHPExcel');
            include ($phpExcelPath . DIRECTORY_SEPARATOR . 'IOFactory.php');
            spl_autoload_register(array(
                'YiiBase',
                'autoload'
            ));
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            if (is_array($sheetData) && count($sheetData) > 0) {
                $result = $sheetData;
            } else {
            }
        }
        catch(Exception $e) {
        }
        return $result;
    }
    public function getHeader() {
        return $this->headers;
    }
    public function checkS($col, $value, $index) {
        return false;
    }
    public function import($data) {
    }
    public function formatData($data) {
        // Tak::KD($data);
        $result = array();
        foreach ($data as $key => $value) {
            $temp = array();
            foreach ($this->cols as $col => $name) {
                $temp[$name] = $value[$col];
            }
            $result[] = $temp;
        }
        $this->data = $result;
        return $result;
    }
    public function load($data) {
        $result = array();
        $errors = array();
        foreach ($data as $index => $value) {
            $row = array();
            foreach ($this->headers as $key => $col) {
                $t = $this->checkS($key, $value[$key], $index);
                if ($t) {
                    $_NAME = $this->getColName($key, $index);
                    $_NAME = str_replace(']', '', $_NAME);
                    $_NAME = str_replace('[', '_', $_NAME);
                    $errors[] = $_NAME;
                } else {
                    $row[$key] = $value[$key];
                }
            }
            //以前是防止重复，加上而已
            // $result[$value['A']] = $row;
            $result[] = $row;
        }
        if (count($errors) > 0) {
            $this->errors = $errors;
            $result = false;
        } else {
            $this->formatData($result);
            $result = true;
        }
        return $result;
    }
    
    public function getTags() {
        $data = $this->getData($this->varfile);
        $result = false;
        // Tak::KD($this->varfile);
        if ($data) {
            $head = $this->getHeader();
            // 清除空列
            $xhead = array_filter(array_shift($data));
            $result = array();
            // 字段不匹配，或者空行
            if ($head != $xhead) {
                // if (count($head) != count($xhead)) {
                // 删除文件
                unlink($this->varfile);
                return $result;
            } else {
                /*$head = array_keys($head);*/
            }
            // Tak::KD($data);
            $error = 0;
            foreach ($data as $value) {
                $temp = array();
                if (is_array($value)) {
                    //第一列不允许为空
                    $icol = trim(current($value));
                    if (strpos($icol, '注意事项') !== false) {
                        break;
                    }
                    if ($icol == '') {
                        $error++;
                        if ($error == 8) {
                            break;
                        }
                        continue;
                    }
                    $error = 0;
                    foreach ($head as $key => $v) {
                        //可能会有ＮＵＬＬ值
                        $temp[$key] = $value[$key] ? $value[$key] : '';
                    }
                    $result[] = $temp;
                }
            }
            return $result;
        }
    }
    
    public function save() {
        $result = $this->validate();
        if ($result) {
            if ($this->file) {
                $newName = date('Ymd-His__') . $this->model . '.' . $this->file->extensionName;
                $folder = Tak::getUserDir($id) . 'temp/import/';
                $root = YiiBase::getPathOfAlias('webroot');
                Tak::MkDirs($root . $folder);
                $varfile = $root . $folder . $newName;
                if ($this->file->saveAs($varfile)) {
                    $this->file = Yii::app()->getBaseUrl() . $folder . $newName;
                    $this->varfile = $varfile;
                }
            }
        }
        return $result;
    }
}
