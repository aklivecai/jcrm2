<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-14 17:17:05
 * @version $Id$
 */
class ProductionController extends Controller {
    public function init() {
        parent::init();
        $this->modelName = 'Production';
    }
    public function loadModel($id, $iserror = true) {
        if ($this->_model === null) {
            $id = $this->getSId($id);
            $m = $this->modelName;
            $m = $m::model();
            $m = $m->findByPk($id);
            if ($m === null && $iserror) {
                $this->error();
            }
            $this->_model = $m;
        }
        return $this->_model;
    }
    /** 保存车间工序用时 **/
    public function actionProcess($id) {
        $model = $this->loadModel($id);
        $this->_setLayout('//layouts/columnCost');
        $itemid = $model->itemid;
        // 查询生产的车间
        $tags = $model->getProducts();
        $process = $model->getProcess();
        $workshops = Workshop::getAll();
        $works = array();
        foreach ($tags as $key => $value) {
            $wid = $value['workshop_id'];
            if (!isset($works[$wid])) {
                $obj = $workshops[$wid];
                $works[$wid]['name'] = $obj['typename'];
                $works[$wid]['process'] = $obj['process'];
            }
            $works[$wid]['product'][] = $value;
        }
        $m = 'M';
        $data = isset($_POST[$m]) ? $_POST[$m] : false;
        
        if ($data && is_array($data)
        //提交过来的车间数不能超过生产的车间数
         && count($works) == count($data)) {
            // Tak::KD($data, 1);
            $errors = array();
            $error = false;
            $pday = new ProductionDays('create');
            $pday->production_id = $itemid;
            $sqls = array(
                'fromid' => Ak::getFormid() ,
                'production_id' => $itemid,
            );
            if (count($process) > 0) {
                //清空旧数据
                ProductionDays::model()->deleteAllByAttributes($sqls);
            }
            foreach ($data as $workshops_id => $work) {
                $error = false;
                // Tak::KD($work);
                // Tak::KD($workshops[$workshops_id]);
                if (!isset($works[$workshops_id])) {
                    $error = '不存在的车间';
                } elseif (!is_array($work) || count($work) == 0 || count($work) > count($workshops[$workshops_id]['process'])) {
                    //有传递工序数据过来
                    $error = '请选择工序';
                }
                if ($error) {
                    $errors[$workshops_id] = $error;
                } else {
                    //车间下的所有工序,在用到的车间里面找
                    $dataprocess = $works[$workshops_id]['process'];
                    foreach ($work as $proid => $v2) {
                        $days = $v2['days'];
                        $planner = $v2['planner'];
                        if (!isset($dataprocess[$proid])) {
                            $error = '不存在的工序';
                        } elseif (!is_numeric($days) || $days <= 0) {
                            $error = '工序用时要大于０';
                        } elseif ($planner == '') {
                            $error = '计划人不能为空';
                        }
                        if ($error) {
                            $errors[$proid] = $error;
                        } else {
                            $pday->setIsNewRecord(true);
                            $pday->workshop_id = $workshops_id;
                            $pday->days = $days;
                            $pday->planner = $planner;
                            $pday->process = $dataprocess[$proid]['typename'];
                            if ($pday->validate() && $pday->save()) {
                            } else {
                                $errors[$proid] = $pday->getErrors();
                            }
                        }
                    }
                }
            }
            /**有错误，删除刚刚插入的信息**/
            if (count($errors) > 0) {
                ProductionDays::model()->deleteAllByAttributes($sqls);
                Tak::KD($errors);
            } else {
                //初次初次配置生产的车间
                //更新成本合算，状态为已经生成生产单
                if ($model->status == 1) {
                    Cost::model()->findByPk($model->itemid)->upProduction();
                    //排期完成
                    $model->upStatus(2);
                }
                $this->redirect(array(
                    '/Production/view',
                    'id' => $id,
                ));
            }
        }
        
        $_process = array();
        
        foreach ($process as $key => $value) {
            $_process[sprintf("%s-%s", $value['workshop_id'], $value['process']) ] = $value;
        }
        // Tak::KD($_process);
        $this->render('process', array(
            'model' => $model,
            'data' => $works,
            'id' => $id,
            'iprocess' => $_process,
        ));
    }
    public function actionView($id) {
        $model = $this->loadModel($id, false);
        if ($model === null) {
            $this->redirect(array(
                '/Cost/Create',
                'id' => $id,
            ));
        }
        //排期中
        if ($model->status == 1) {
            $this->redirect(array(
                'process',
                'id' => $id,
            ));
        }
        $this->_setLayout('//layouts/columnCost');
        /**查询核算中的产品**/
        $products = $model->getProducts();
        // Tak::KD($products);
        /**查询生产中的工序用时**/
        $process = $model->getProcess();
        // Ak::KD($process);
        if (count($process) == 0) {
            $this->redirect(array(
                'process',
                'id' => $id,
            ));
        }
        $data = array();
        foreach ($process as $key => $value) {
            if (!isset($data[$value['workshop_id']])) {
                $data[$value['workshop_id']] = array(
                    'process' => array() ,
                    'product' => array() ,
                );
            }
            // $data[$value['workshop_id']]['product'][] = $value;
            $data[$value['workshop_id']]['process'][$value['process']] = $value;
        }
        foreach ($products as $key => $value) {
            if (isset($data[$value['workshop_id']])) {
                $data[$value['workshop_id']]['product'][] = $value;
            }
        }
        $_workshops = Workshop::getAll();
        $workshop_ids = array_keys($data);
        $workshops = array();
        foreach ($workshop_ids as $wid) {
            if (isset($_workshops[$wid])) {
                $workshops[$wid] = array();
                $process = array();
                $_process = $data[$wid]['process'];
                $_status = 0;
                // Tak::KD($_process);
                foreach ($_workshops[$wid]['process'] as $v) {
                    if (isset($_process[$v['typename']])) {
                        $pro = array(
                            'name' => $v['typename'],
                            'value' => $_process[$v['typename']]['days'],
                            'planner' => $_process[$v['typename']]['planner'],
                            'itemid' => $_process[$v['typename']]['itemid'],
                            'status' => $_process[$v['typename']]['progress'],
                        );
                        //统计车间多少个工序是完成
                        $_process[$v['typename']]['progress'] == 1 && $_status+= 1;
                        //工序的进度
                        $pro['list'] = ProductionProgresss::getListByProcessid($pro['itemid']);
                        $process[] = $pro;
                    }
                }
                // 排序好的工序
                $data[$wid]['process'] = $process;
                //车间状态，工序是否都完成
                $data[$wid]['status'] = count($_process) == $_status;
                //车间的名字
                $data[$wid]['name'] = $_workshops[$wid]['typename'];
            }
        }
        // Tak::KD($data);
        $this->render('view', array(
            'model' => $model,
            'data' => $data,
            'id' => $id,
        ));
    }
    public function actionIndex() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        $this->render($this->templates['index'], array(
            'model' => $model,
        ));
    }
    /**
     * 工序进度
     * @param  int  $id     生产编号
     * @param  int  $itemid 工序用时编号
     * @param  string $val  完成进度（10%,over=>已完成）
     * @return [type]          [description]
     */
    public function actionProductionProgresss($id) {
        $val = Tak::getPost('val', false);
        $itemid = Tak::getPost('itemid', false);
        if (!$val || !$itemid) {
            $this->error();
        }
        $model = $this->loadModel($id);
        $productiondays = ProductionDays::model()->findByPk($itemid);
        $result = '';
        if ($productiondays != null) {
            $mpp = ProductionProgresss::model()->findByAttributes(array(
                'process_id' => $productiondays->itemid
            ) , array(
                'order' => 'add_time DESC'
            ));
            if ($mpp == null) {
                $mpp = new ProductionProgresss('create');
                $mpp->process_id = $productiondays->itemid;
                $mpp->production_id = $model->itemid;
            } elseif ($mpp->status == 2) {
                // 工序已经完成
                $result = '工序已经完成';
            } else {
                $mpp->setIsNewRecord(true);
            }
            if ($result == '') {
                if ($val == 'over') {
                    $mpp->progress = '完成';
                    $mpp->status = 2;
                } else {
                    $mpp->progress = $val;
                }
                if ($mpp->validate() && $mpp->save()) {
                } else {
                    $errors = $mpp->getErrors();
                    foreach ($errors as $key => $value) {
                        $result.= sprintf("\n%s", current($value));
                    }
                }
            }
        } else {
            $result = '不存在工序进度记录';
        }
        echo $result;
    }
    
    public function writeProduct($data) {
        $htmls = array(
            '<ol class="mov-products">'
        );
        foreach ($data as $key => $value) {
            $numbers = Tak::getNums($value['numbers']);
            $names = implode(' , ', array(
                $value['type'],
                $value['name'],
                $value['spec'],
                $value['color'],
            ));;
            // $numbers =Tak::tagNum($numbers,'label-info');
            $htmls[] = sprintf("<li>(%s) - 数量:%s</li>", $names, $numbers);
        }
        $htmls[] = '</ol>';
        $html = implode("", $htmls);
        if (count($data) > 3) {
            $html = sprintf('<div class="mov-product">%s<a class="not-printf more-product">&gt;&gt;更多</a><a class="not-printf more-product-hide">&gt;&gt;收起</a></div>', $html);
        }
        return $html;
    }
    public function getLink($id, $status) {
        $name = Production::getStateText($status);
        return JHtml::link($name, array(
            "View",
            "id" => $this->setSid($id) ,
        ) , array(
            "class" => "target-win"
        ));
    }
}
