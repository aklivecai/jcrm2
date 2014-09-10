<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.org)
 * @date    2014-03-13 10:08:23
 * @version $Id$
 */
class JImportProduct extends JImportForm {
    
    public $headers = array(
        'A' => '产品型号',
        'B' => '货物分类',
        'C' => '材料',
        'D' => '规格',
        'E' => '颜色',
        'F' => '计量单位',
        'G' => '备注',
        'H' => '单价',
        'I' => '库存',
    );
    public $cols = array(
        'A' => 'name',
        'B' => 'catename',
        'C' => 'material',
        'D' => 'spec',
        'E' => 'color',
        'F' => 'unit',
        'G' => 'note',
        'H' => 'price',
        'I' => 'stocks',
    );
    public $model = 'product';
    public function checkS($col, $value, $index) {
        $result = false;
        if (($col == 'A' || $col == 'B') && $value == '') {
            $result = true;
        } elseif ($col == 'I' && $value != '') {
            if (is_numeric($value) || $value == 0) {
                $v = $value;
                $result = !($v >= 0);
            } else {
                $result = true;
            }
        } elseif ($col == 'H' && $value != '') {
            $result = !Tak::isPrice($value);
        }
        return $result;
    }
    
    private $newProducts = 0;
    private $arr = null;
    
    private $cates = null;
    private $cateids = null;
    private $cate = null;
    private $newCates = array();
    const PRE_STR = '`';
    /*$str =  "分类/分类1/分类2/分类3"*/
    
    private $movings = null;
    public function init() {
        parent::init();
        $this->arr = Tak::getOM();
        unset($this->arr['itemid']);
        $this->arr['note'] = '导入';
    }
    
    public function getMoving() {
        if ($this->movings === null) {
            $this->movings = new Movings('create');
            $this->movings->initak(1);
            $this->movings->attributes = $this->arr;
        }
        return $this->movings;
    }
    
    public function import($warehouse_id = false) {
        if (!$warehouse_id) {
            $warehouse_id = Tak::getPost('warehouse_id', false);
        }
        $arr = $this->arr;
        $cates = $this->getCates();
        $sqls = array();
        $logfile = sprintf('%s-test.log', Ak::getFormid());
        $strMsg = '';
        // 不记录日志
        AdminLog::$isLog = false;
        
        $product = new Product('create');
        $product->attributes = $arr;
        
        $stock = new Stocks('create');
        $stock->attributes = $arr;
        
        $tags = array();
        
        $itemid = Tak::fastUuid();
        $data = $this->data;
        $transaction = Tak::db(true)->beginTransaction();
        try {
            foreach ($data as $key => $value) {
                $itemid = Tak::numAdd($itemid, $key + 2);
                $catid = $this->getCateId($value['catename'], $key * 2);
                $stocks = $value['stocks'] == '' ? 0 : $value['stocks'];
                $value['stocks'] = $stocks;
                if ($value['price'] == '') {
                    $value['price'] = 0;
                }
                $product->attributes = $value;
                if ($product->itemid > 0) {
                    $product->itemid = $itemid;
                    $product->setIsNewRecord(true);
                }
                // Tak::KD($product->attributes);
                // Tak::KD($catid);
                $product->typeid = $catid;
                if ($product->save()) {
                    if ($warehouse_id > 0) {
                        if ($stock->itemid > 0) {
                            $stock->itemid = $itemid;
                            $stock->setIsNewRecord(true);
                        }
                        $stock->attributes = array(
                            'product_id' => $product->itemid,
                            'warehouse_id' => $warehouse_id,
                            'stocks' => $stocks,
                        );
                        $stock->warehouse_id = $warehouse_id;
                        #保存库存信息，导入仓库
                        if ($stock->save()) {
                            $tags[$product->itemid] = array(
                                'itemid' => $product->itemid,
                                'product_id' => $product->itemid,
                                'fromid' => $product->fromid,
                                'type' => 1,
                                'warehouse_id' => $warehouse_id,
                                'time_stocked' => $product->add_time,
                                'numbers' => $stocks,
                                'price' => $product->price,
                                'note' => '导入',
                            );
                            // exit;
                            #保存一个到产品出入库里面，编号和产品编号一致
                            /*
                            $pmoving = new ProductMoving('create');
                            $pmoving->attributes = array(
                                'itemid' => $product->itemid,
                                'movings_id' => $product->itemid,
                                'product_id' => $product->itemid,
                                'fromid' => $product->fromid,
                                'type' => 1,
                                'warehouse_id' => $warehouse_id,
                                'time_stocked' => $product->add_time,
                                'numbers' => $stocks,
                                'price' => $product->price,
                                'note' => '导入',
                            );
                            if (!$pmoving->save()) {
                                $strMsg.= "\n" . var_export($pmoving->getErrors() , TRUE);
                                Tak::KD($strMsg);
                                exit;
                            }
                            */
                        } else {
                            $strMsg.= "\n" . var_export($stock->getErrors() , TRUE);
                        }
                    }
                    unset($data[$key]);
                    $this->newProducts++;
                } else {
                    $strMsg.= "\n" . var_export($product->getErrors() , TRUE);
                }
            }
        }
        catch(Exception $e) {
            $transaction->rollback();
            $error = $e->getMessage();
            Tak::K($error, $logfile);
            if (strpos($error, 'Duplicate')) {
                $this->data = $data;
                $this->import($warehouse_id);
            }
            return false;
        }
        if ($warehouse_id > 0 && count($tags) > 0) {
            $movings = $this->getMoving();
            if ($movings->primaryKey == 0) {
                $movings->warehouse_id = $warehouse_id;
                $movings->typeid = 0;
                $movings->time_stocked = $movings->time = $arr['time'];
                $movings->enterprise = '导入';
                $movings->type = 1;
                $movings->note = '初始化导入';
                $movings->us_launch = Manage::getNameById(Ak::getManageid());
                $movings->save();
                $this->movings = $movings;
            }
            foreach ($tags as $key => $value) {
                $pmoving = new ProductMoving('create');
                $pmoving->attributes = $value;
                $pmoving->movings_id = $movings->primaryKey;
                $pmoving->save();
            }
        }
        
        $str = '
            <ul>
                <li>
                    导入产品型号：%s个<a href="%s">点击浏览</a>
                </li>%s
            </ul>
        ';
        $strCate = '';
        if (count($this->newCates) > 0) {
            $_str = '';
            foreach ($this->newCates as $value) {
                $_str.= sprintf("<span class='label label-success'>%s</span> ", $value);
            }
            $strCate = sprintf('<li>导入货物分类：%s</li>', $_str);
        }
        
        $tarr = array(
            'add_time' => $arr['time'],
            'add_ip' => $arr['ip'],
        );
        $url = Yii::app()->createUrl('Product/Admin?');
        $url.= Tak::createMUrl($tarr, $this->getModel());
        $str = sprintf($str, $this->newProducts, $url, $strCate);
        //删除导入数据缓存html
        if (!YII_DEBUG) {
            Tak::deleteUCache($this->model);
        }
        // 记录日志
        AdminLog::$isLog = true;
        //记录日志
        AdminLog::log($str, $arr);
        //设置提示消息，成功导入多少数据，新建多少分类
        Tak::setFlash($str);
        Tak::K(count($this->data) , $logfile);
        Tak::K($strMsg, $logfile);
    }
    // $name = '成品/11/22/33/44/55/A/B/C/D/E/F/G';
    // $name = '//成品/\\//111/22/33/44/55.55/AA/BB/CC/DD/EE/FFF/GGGG/';
    public function getCateId($name, $ikeys = 0) {
        $cates = $this->getCates();
        $strPRE = self::PRE_STR;
        #替换空格
        $name = str_replace(' ', '', $name);
        #替换多个重复的
        $name = preg_replace('/[\/\\\\]{1,}/', '/', $name);
        $names = str_replace('/', $strPRE, $name);
        #查找字符串的首次出现
        if (stripos($names, $strPRE) !== false && stripos($names, $strPRE) == 0) {
            $names = substr($names, 1);
        }
        #查找字符串的最后出现
        if (strpos($names, $strPRE) == strlen($names)) {
            $names = substr($names, 0, strlen($names) - 1);
        }
        $pid = 0;
        $cid = 0;
        $data = array(
            $names
        );
        if (isset($cates[$names])) {
            //分类已经存在
            $cid = $cates[$names];
        } elseif (strpos($names, $strPRE) !== false) {
            //有多级分类
            $str = $names;
            $isok = true;
            while ($isok) {
                $int = strrpos($str, $strPRE);
                if ($int !== false) {
                    $str = substr($str, 0, $int);
                    if (isset($cates[$str])) {
                        $pid = $cates[$str];
                        $isok = false;
                        break;
                    } else {
                        array_unshift($data, $str);
                    }
                } else {
                    $isok = false;
                    break;
                }
            }
        } else {
            //一级分类，没有子类
            ;
        }
        if ($cid == 0) {
            //确保新插入的分类不缓存,读取最新的分类
            Category::$isNew = true;
            foreach ($data as $key => $value) {
                $int = strrpos($value, $strPRE);
                if ($int === false) {
                    $catename = $value;
                } else {
                    $ilen = strlen($str);
                    $catename = substr($value, $int + 1);
                }
                $ikeys+= 2;
                if ($this->cate === null) {
                    $cate = new Category('create');
                    $arr = $this->arr;
                    $cate->attributes = $arr;
                    $cate->setModel($this->model);
                } else {
                    $cate = $this->cate;
                }
                $cate->parentid = $pid;
                if ($cate->catid > 0) {
                    $cate->catid+= $ikeys;
                }
                $cate->catename = $catename;
                $cate->setIsNewRecord(true);
                if (!$cate->save()) {
                    Tak::KD($cate->getErrors());
                }
                //记录新增分类的名字
                $this->newCates[] = $cate->catename;
                //保存分类对象
                $this->cate = $cate;
                //保存刚刚插入的分类
                $pid = $cates[$value] = $cate->catid;
            }
            //取消读取最新的分类
            Category::$isNew = false;
            //更新全部分类里
            $this->cateids = $cates;
            $cid = $pid;
        }
        // Tak::KD($cid);
        return $cid;
    }
    public function getCates() {
        if ($this->cateids == null) {
            $sql = "SELECT catid,catename,parentid,child FROM :table WHERE  fromid=:fromid AND module=':module' ORDER BY listorder DESC,catid ASC";
            $sql = strtr($sql, array(
                ':table' => Category::$table,
                ':module' => 'product',
                ':fromid' => Tak::getFormid() ,
            ));
            $tags = Tak::db(true)->createCommand($sql)->queryAll();
            $result = array();
            $farr = array();
            $cateids = array();
            foreach ($tags as $key => $value) {
                $cateids[$value['catename']] = $value['catid'];
                $result[$value['catid']] = $value;
                /**
                 * 有子类的一级分类
                 */
                if ($value['child'] > 0 && $value['parentid'] == 0) {
                    $farr[$value['catid']] = $value;
                }
            }
            $tmep = $this->cates = $result;
            $this->cateids = $cateids;
            foreach ($farr as $key => $value) {
                $this->getSetSubCate($value['catename'], $key, $tmep);
                unset($tmep[$key]);
            }
        }
        return $this->cateids;
    }
    
    private function getSetSubCate($str, $id, &$data) {
        if (!isset($this->cates[$id]) || $this->cates[$id]['child'] == 0) {
            return false;
        }
        foreach ($data as $key => $value) {
            if ($id == $value['parentid']) {
                $catename = $str . self::PRE_STR . $value['catename'];
                $this->cateids[$catename] = $key;
                unset($data[$kye]);
                $this->getSetSubCate($catename, $key, $data);
            }
        }
    }
}
