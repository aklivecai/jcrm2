<?php
/**
 * !
 * @authors aklivecai (aklivecai@gmail.org)
 * @date    2014-03-15 08:20:35
 * @version $Id$
 */

class JImportClientele extends JImportForm {
    public $model = 'clientele';
    public $headers = array(
        'A' => '客户名称',
        'B' => '地址',
        'C' => '电话',
        'D' => '传真',
        'E' => '邮箱',
        'F' => '网站',
        'G' => '备注',
        'H' => '联系人',
        'I' => '部门',
        'J' => '职位',
        'K' => '办公电话',
        'L' => '手机',
    );
    public $cols = array(
        'A' => 'clientele_name',
        'B' => 'address',
        'C' => 'telephone',
        'D' => 'fax',
        'E' => 'email',
        'F' => 'www',
        'G' => 'note',
        'H' => 'nicename',
        'I' => 'department',
        'J' => 'position',
        'K' => 'phone',
        'L' => 'mobile',
    );
    
    public function checkS($col, $value, $index) {
        $result = false;
        if (($col == 'A') && $value == '') {
            $result = true;
        }
        return $result;
    }
    
    public function import($manageid = false) {
        if (!$manageid) {
            $manageid = Tak::getPost('manageid', false);
        }
        if (!$manageid) {
            $this->script = '
                alert("请选择导入到的业务员");
                parent.gotoElem("#import-manageid");
            ';
            return false;;
        }
        $arr = Tak::getOM();
        unset($arr['itemid']);
        $arr['note'] = '导入';
        AdminLog::$isLog = false;
        $model = new Clientele('create');
        $model->attributes = $arr;
        
        $contact = new ContactpPrson('create');
        
        $newContact = 0;
        $newModels = 0;
        $itemid = Tak::fastUuid();
        // Tak::KD($this->data);
        foreach ($this->data as $key => $value) {
            $itemid = Tak::numAdd($itemid, $key + 2);
            
            $value['manageid'] = $manageid;
            $model->attributes = $value;
            // Tak::KD($model->attributes);
            $model->industry = 0;
            if ($model->primaryKey > 0) {
                $model->itemid = $itemid;
                $model->setIsNewRecord(true);
            }
            if (!$model->checkRepetition('clientele_name', array())) {
                $model->clientele_name.= Tak::timetodate($arr['time'], 6);
            }
            if ($model->save()) {
                if ($value['nicename']) {
                    if ($contact->primaryKey > 0) {
                        $contact->itemid = $itemid;
                        $contact->setIsNewRecord(true);
                    }
                    $contact->attributes = $value;
                    $contact->clienteleid = $model->primaryKey;
                    if ($contact->save()) {
                        $newContact++;
                    } else {
                        // Tak::KD();
                        
                        
                    }
                }
                $newModels++;
            } else {
                Tak::KD($model->getErrors());
                return false;
            }
        }
        AdminLog::$isLog = true;
        $str = '
            <ul>
                <li>
                    导入%s：%s个<a href="%s">点击浏览</a>
                </li>%s
            </ul>
        ';
        $strContact = '';
        if ($newContact > 0) {
            $strContact = sprintf('<li>导入%s：%s</li>', Tk::g('ContactpPrson') , $newContact);
        }
        $tarr = array(
            'add_time' => $arr['time'],
            'add_ip' => $arr['ip'],
        );
        $url = Yii::app()->createUrl('Clientele/Admin?');
        $url.= Tak::createMUrl($tarr, $this->getModel());
        $str = sprintf($str, Tk::g($this->getModel()) , $newModels, $url, $strContact);
        AdminLog::log($str, $arr);
        Tak::deleteUCache($this->model);
        Tak::setFlash($str);
    }
    
    public function getCates() {
        $sql = "SELECT address_groups_id,name FROM :table WHERE  fromid=:fromid ORDER BY name ASC";
        $sql = strtr($sql, array(
            ':table' => AddressGroups::$table,
            ':fromid' => Tak::getFormid() ,
        ));
        $tags = Ak::db(true)->createCommand($sql)->queryAll();
        $result = array();
        foreach ($tags as $key => $value) {
            $result[$value['name']] = $value['address_groups_id'];
        }
        return $result;
    }
}
