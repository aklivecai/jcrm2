<?php
/**
 * TRUNCATE TABLE `tak_contact531`
 */
class ImportForm531 extends ImportForm {
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
            $strErrors = array();
            $totals = is_array($sheetData) ? count($sheetData) : 0;
            if ($totals > 1) {
                $totals-= 1;
                $arr = Tak::getOM();
                $time = $arr['time'];
                $m = new Contact531('create');
                $m->add_us = $m->manageid = $arr['manageid'];
                $m->add_time = $arr['time'];
                $m->add_ip = $arr['ip'];
                $m->isLog = false;
                $errors = 0;
                $number = 0;
                
                foreach ($sheetData as $key => $value) {
                    if ($value['A'] == '' || $key == 1) {
                        $key > 1 && $errors++;
                        if ($errors > 20) {
                            break;
                        }
                        continue;
                    }
                    try {
                        $keys = array(
                            'clientele_name',
                            'nicename',
                            'mobile',
                            'phone',
                            'address',
                            'business',
                            'web',
                        );
                        $ls = array(
                            'A',
                            'B',
                            'C',
                            'D',
                            'E',
                            'F',
                            'G'
                        );
                        $attrs = array();
                        foreach ($ls as $k1 => $v1) {
                            $attrs[$keys[$k1]] = $value[$v1];
                        }
                        if (count($keys) == count($value)) {
                            $attrs = array_combine($keys, $value);
                        }
                        $m->attributes = $attrs;
                        if ($m->itemid > 0) {
                            $m->itemid = Tak::numAdd($m->itemid, $key + 2);
                            $m->isNewRecord = true;
                        }
                        if ($m->validate() && $m->save()) {
                            $number++;
                        } else {
                            $strErrors[] = Tak::getMsgByErrors($m->getErrors() , false);
                            $errors++;
                        }
                    }
                    catch(Exception $e) {
                        $errors++;
                    }
                }
                $str = sprintf("总数%s ,成功导入   %s 个 , 失败%s", $totals, $number, $errors);
                if (count($strErrors) > 0) {
                    $str.= '<ul>' . implode('', $strErrors) . '</ul>';
                }
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
                    $_file = $root . $folder . $newName;
                    // Ak::setState('_file', $_file);
                    $result = $this->import($_file);
                }
            }
        }
        return $result;
    }
}
