<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.org)
 * @date    2014-03-15 05:41:53
 * @version $Id$
 */

class JImportAddressbook extends JImportForm {
    public $headers = array(
        'A' => '姓名',
        'B' => '部门',
        'C' => '职位',
        'D' => '性别',
        'E' => 'Email',
        'F' => '座机',
        'G' => '电话',
        'H' => '联系地址',
        'I' => '备注',
    );
    public $cols = array(
        'A' => 'name',
        'B' => 'catename',
        'C' => 'position',
        'D' => 'sex',
        'E' => 'email',
        'F' => 'telephone',
        'G' => 'phone',
        'H' => 'address',
        'I' => 'note',
    );
    public $model = 'addressBook';
    
    public function checkS($col, $value, $index) {
        $result = false;
        if (($col == 'A' || $col == 'B') && $value == '') {
            $result = true;
        }
        return $result;
    }
    public function import() {
        $arr = Tak::getOM();
        unset($arr['itemid']);
        $arr['note'] = '导入';
        $cates = $this->getCates();
        $sqls = array();
        AdminLog::$isLog = false;
        
        $cate = new AddressGroups('create');
        $cate->attributes = $arr;
        
        $model = new AddressBook('create');
        $model->attributes = $arr;
        
        $newCates = array();
        $newProducts = 0;
        // Tak::KD($this->data,1);
        $itemid = Tak::fastUuid();
        foreach ($this->data as $key => $value) {
            $itemid = Tak::numAdd($itemid, 2);
            
            if (isset($cates[$value['catename']])) {
                $groups_id = $cates[$value['catename']];
            } else {
                if ($cate->address_groups_id > 0) {
                    $cate->address_groups_id+= $key + 2;
                }
                $cate->name = $value['catename'];
                $cate->setIsNewRecord(true);
                $cate->save();
                $newCates[] = $cate->name;
                $groups_id = $cates[$value['catename']] = $cate->address_groups_id;
            }
            $model->attributes = $value;
            
            if ($model->sex == '男') {
                $model->sex = 1;
            } elseif ($model->sex == '女') {
                $model->sex = 2;
            } else {
                $model->sex = 0;
            }
            
            if ($model->itemid > 0) {
                $model->itemid = $itemid;
                $model->setIsNewRecord(true);
            }
            $model->groups_id = $groups_id;
            if ($model->save()) {
                $newProducts++;
            } else {
                // Tak::KD($model->getErrors(),1);
                
                
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
        $strCate = '';
        if (count($newCates) > 0) {
            $_str = '';
            foreach ($newCates as $value) {
                $_str.= sprintf("<span class='label label-success'>%s</span> ", $value);
            }
            $strCate = sprintf('<li>导入%s：%s</li>', Tk::g('AddressGroups') , $_str);
        }
        
        $tarr = array(
            'add_time' => $arr['time'],
            'add_ip' => $arr['ip'],
        );
        $url = Yii::app()->createUrl('AddressBook/Admin?');
        $url.= Tak::createMUrl($tarr, $this->getModel());
        $str = sprintf($str, Tk::g($this->getModel()) , $newProducts, $url, $strCate);
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
