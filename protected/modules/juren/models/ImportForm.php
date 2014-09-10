<?php
class ImportForm extends CFormModel {
    public $file = 10;
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
    
    public function import($file) {
        $inputFileName = $file;
        $time = false;
        // $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
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
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            if (is_array($sheetData) && count($sheetData) > 1) {
                $arr = array();
                
                $arr = Tak::getOM();
                $time = $arr['time'];
                $m = new TestMemeber();
                $m->add_us = $m->manageid = $arr['manageid'];
                $m->add_time = $arr['time'];
                $m->add_ip = $arr['ip'];
                $m->isLog = false;
                $number = 0;
                foreach ($sheetData as $key => $value) {
                    if ($value['A'] == '' || $key == 1) {
                        continue;
                    }
                    try {
                        $number++;
                        $m->company = $value['A'];
                        $m->email = $value['B'];
                        $m->isNewRecord = true;
                        if (!$m->save()) {
                            $err = $m->getErrors();
                            foreach ($err as $key => $value) {
                                $this->addError('', $value[0]);
                            }
                        }
                    }
                    catch(Exception $e) {
                    }
                }
                $str = "成功导入 {$number} 个";
                AdminLog::log($str);
                Yii::app()->user->setFlash('msg', $str);
                return $time;
            }
        }
        catch(Exception $e) {
            echo $e->getMessage();
            Yii::app()->end();
        }
    }
    
    public function save() {
        $result = $this->validate();
        if ($result) {
            if ($this->file) {
                $newName = date('Ymd-His') . '.' . $this->file->extensionName;
                $root = YiiBase::getPathOfAlias('webroot');
                $webroot = Yii::app()->getBaseUrl();
                $folder = '/upload/temp/';
                if (!is_dir($root . $folder)) {
                    if (!mkdir($root . $folder, 0, true)) {
                        throw new Exception('创造文件夹失败...');
                    }
                }
                if ($this->file->saveAs($root . $folder . $newName)) {
                    $this->file = Yii::app()->getBaseUrl() . $folder . $newName;
                    $result = $this->import($root . $folder . $newName);
                }
            }
        }
        return $result;
    }
}
