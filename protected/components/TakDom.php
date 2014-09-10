<?php
class TakDom {
    private $document = null;
    public function TakDom() {
        $this->init();
    }
    public function getDom() {
        return $this->document;
    }
    public function init() {
        try {
            spl_autoload_unregister(array(
                'YiiBase',
                'autoload'
            ));
            $dir = Yii::getPathOfAlias('application.extensions.querypath');
            include ($dir . DIRECTORY_SEPARATOR . 'qp.php');
            spl_autoload_register(array(
                'YiiBase',
                'autoload'
            ));
        }
        catch(Exception $e) {
        }
    }
    public function getFiles($data) {
        $files = array();
        $msgs = array();
        try {
            if (strpos($data, '"utf-8"') === false) {
                //防止中文了乱码
                // $data = sprintf('<meta charset="utf-8">%s' ,$data);
                $data = sprintf('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>%s', $data);
                // Tak::KD($data);
                
            }
            $list = qp($data)->find('.wf_field');
        }
        catch(Exception $e) {
            return false;
        }
        if ($list !== null && $list->length > 0) {
            foreach ($list as $key => $value) {
                if ($value->attr('otype')) {
                    $name = $value->attr('name');
                    
                    // Tak::KD($name);
                    // Tak::KD($value->html() ,1);
                    
                    if (isset($files[$name])) {
                        $msgs[] = $name;
                    } else {
                        $odata = $value->attr('odata'); //后期得过滤，如果数值不对也是错误的，不能保存，不然后面不能解析
                        if ($odata == '' || !CJSON::decode($odata)) {
                            /*$msgs[] = $name;*/
                        } else {
                            $files[$name] = array(
                                'field_name' => $name,
                                'dvalue' => $value->attr('dvalue') ,
                                'otype' => $value->attr('otype') ,
                                'odata' => $odata,
                                'style' => $value->attr('style') ,
                                'html' => $value->html() ,
                            );
                        }
                    }
                }
            }
        }
        return array(
            'files' => $files,
            'msgs' => $msgs
        );
    }
}
