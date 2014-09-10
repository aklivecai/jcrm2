<?php
class ToolsController extends JController {
    protected $fid = 0;
    protected $msgs = array(
        'totals' => 0
    );
    protected $isDel = true;
    protected $ovetime = null;
    
    protected $actions = array(
        '0' => '操作',
        'product' => '清空产品',
        'order' => '清空订单',
        'production' => '生产管理',
        'clientele' => '客户',
        'wage' => '工资',
        'log' => '日志',
        '-1' => '----------------',
        'view' => '查看',
        'all' => '清空全部用数据',
        'test' => '还原默认配置的测试账号',
    );
    private function getDb($id) {
        $db = Tak::db(true, $id)->createCommand('');
        return $db;
    }
    public function init() {
        parent::init();
        $this->menu = array(
            'company' => array(
                'label' => Tk::g('Company') ,
                'url' => array(
                    'company/admin'
                )
            ) ,
            'manage' => array(
                'label' => Tk::g('Manage') ,
                'url' => array(
                    'Manage'
                )
            ) ,
            'database' => array(
                'label' => Tk::g('Database') ,
                'url' => array(
                    'Database'
                )
            ) ,
            'wipe' => array(
                'label' => Tk::g('Wipe') ,
                'url' => array(
                    'Wipe'
                )
            ) ,
        );
    }
    public function actionCreate() {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionAdmin() {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionUpdate($id) {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionDelete($id) {
        throw new CHttpException(404, '所请求的页面不存在。');
    }
    public function actionIndex() {
        $fid = Tak::getPost('fid', false);
        $action = Tak::getPost('action', false);
        $_ids = Tak::getUCache('tools - ids');
        $ids = new CMap();
        if ($_ids) {
            $ids->copyFrom($_ids);
        } else {
            $ids->add('3930', '木之源家具');
        }
        
        if ($action && $fid && $fid > 0) {
            $com = false;
            if ($ids->contains($fid)) {
                $id = $fid;
            } else {
                $_m = Test9Memeber::model()->findByPk($fid);
                if ($_m == null) {
                    $str = "不存在测试账号";
                    Tak::setFlash($str, 'msg');
                } else {
                    $id = $_m->primaryKey;
                    $ids->add($id, $_m->company);
                }
            }
            if ($id > 0) {
                $this->fid = $id;
                $fun = 'clear' . ucwords($action);
                if (method_exists($this, $fun)) {
                    if ($action == 'test') {
                        $this->fid = 3930;
                        $this->ovetime = strtotime('2014-08-18 23:59:59');
                    }
                    $this->$fun();
                } else {
                    Tak::setFlash('不存在方法', 'msg');
                }
                if (count($this->msgs) > 1) {
                    $str = '';
                    foreach ($this->msgs as $key => $value) {
                        $str.= sprintf(' <li> <strong > %s </strong >: %s </li> ', $value, $key);
                    }
                    $htmls = sprintf(' <ul> %s </ul > ', $str);
                    Tak::setFlash($htmls, 'msg');
                }
            }
        }
        $_ids = $ids->toArray();
        Tak::setUCache('tools - ids', $_ids);
        $this->render('index', array(
            'fid' => $fid,
            'aciton' => $action,
            'ids' => $_ids,
        ));
    }
    public function clearView() {
        $this->isDel = false;
        $this->clearAll();
    }
    public function excSqls($sqls) {
        if ($this->fid <= 0) {
            return false;
        }
        if ($this->isDel) {
            $this->del($sqls);
        } else {
            $this->view($sqls);
        }
    }
    private function view($sqls) {
        $command = Tak::getDb('db')->createCommand('');
        $arr = array(
            ':fromid' => $this->fid
        );
        $totals = 0;
        foreach ($sqls as $key => $value) {
            $sql = str_replace('DELETE FROM', 'SELECT COUNT(1) FROM', $value);
            $command->text = $sql;
            $totals = $command->queryScalar($arr);
            $this->msgs['totals'] = $this->msgs['totals'] + $totals;
            $_name = is_string($key) ? $key : $sql;
            $this->msgs[$_name] = $totals;
        }
    }
    private function del($sqls) {
        $command = Tak::getDb('db')->createCommand('');
        $arr = array(
            ':fromid' => $this->fid
        );
        $totals = 0;
        foreach ($sqls as $key => $value) {
            while (1) {
                //每次只做1000条
                $command->text = sprintf(' %s LIMIT 1000', $value);
                // $command->text = $value;
                $rowCount = $command->execute($arr);
                if ($rowCount == 0) {
                    // 没得可删了，退出！
                    // Tak::KD($command->text);
                    // 统计删除的总数
                    // 执行语句删除的总数
                    $this->msgs['totals'] = $this->msgs['totals'] + $totals;
                    $_name = (is_string($key) ? $key . ' - ' : '') . $value;
                    $this->msgs[$_name] = $totals;
                    $totals = 0;
                    break;
                } else {
                    $totals+= $rowCount;
                }
                // 每次都要休息一会儿
                usleep(50000);
            }
        }
    }
    
    public function clearAll() {
        $actions = $this->actions;
        unset($actions['0']);
        unset($actions['-1']);
        unset($actions['test']);
        unset($actions['all']);
        unset($actions['view']);
        foreach ($actions as $key => $value) {
            if (!is_numeric($key)) {
                $fun = 'clear' . ucwords($key);
                if (method_exists($this, $fun)) {
                    $this->$fun();
                } else {
                }
            }
        }
    }
    
    public function clearClientele() {
        $sqls = array(
            'clientele' => "DELETE FROM {{clientele}} WHERE fromid=:fromid  ",
            'contactp_prson' => "DELETE FROM {{contactp_prson}} WHERE fromid=:fromid  ",
            'contact' => "DELETE FROM {{contact}} WHERE fromid=:fromid  ",
            'address_book' => "DELETE FROM {{address_book}} WHERE fromid=:fromid  ",
            'address_groups' => "DELETE FROM {{address_groups}} WHERE fromid=:fromid  ",
            'subordinate' => "DELETE FROM {{subordinate}} WHERE fromid=:fromid  ",
            'manage' => "DELETE FROM {{manage}} WHERE fromid=:fromid  AND manageid>fromid AND user_name<>'admin'  ",
            'events' => "DELETE FROM {{events}} WHERE fromid=:fromid  ",
            'rbac_authassignment' => "DELETE FROM {{rbac_authassignment}} WHERE fromid=:fromid  AND itemname>50000000",
            'rbac_authitemchild' => "DELETE FROM {{rbac_authitemchild}} WHERE parent IN (SELECT name FROM {{rbac_authitem}} WHERE fromid=:fromid AND name>50000000)",
            
            'rbac_authitem' => "DELETE FROM {{rbac_authitem}} WHERE  fromid=:fromid AND name>50000000",
        );
        $this->excSqls($sqls);
    }
    /**
     * 工资
     * @return [type] [description]
     */
    public function clearWage() {
        $sqls = array(
            'wage' => "DELETE FROM {{wage}} WHERE fromid=:fromid ",
            'department' => "DELETE FROM {{department}} WHERE fromid=:fromid ",
            'department_worker' => "DELETE FROM {{department_worker}} WHERE fromid=:fromid ",
            'department_price' => "DELETE FROM {{department_price}} WHERE fromid=:fromid ",
        );
        $this->excSqls($sqls);
    }
    public function clearOrder() {
        /*清空数据,订单*/
        $sqls = array(
            'order_files' => "DELETE FROM  {{order_files}} WHERE action_id  IN( SELECT oflow.itemid FROM {{order_flow}} AS oflow
                                            INNER JOIN {{order}}  AS o 
                                                ON oflow.order_id=o.itemid 
                                            WHERE o.fromid=:fromid  UNION ALL SELECT itemid FROM {{order_product}}  WHERE fromid=:fromid)",
            
            'order_product' => "DELETE FROM {{order_product}} WHERE order_id IN(SELECT itemid FROM {{order}} WHERE fromid=:fromid ) ",
            
            'order_flow' => "DELETE FROM {{order_flow}} WHERE order_id IN(SELECT itemid FROM {{order_info}}  WHERE fromid=:fromid )",
            
            'order_review' => "DELETE FROM {{order_review}} WHERE fromid=:fromid ",
            'order' => "DELETE FROM {{order}} WHERE fromid=:fromid ",
            'order_info' => "DELETE FROM {{order_info}} WHERE fromid=:fromid ",
        );
        
        $this->excSqls($sqls);
    }
    
    private function clearProduction() {
        /*成本核算，生产管理*/
        $sqls = array(
            'cost' => "DELETE FROM {{cost}} WHERE fromid=:fromid ",
            'cost_product' => "DELETE FROM {{cost_product}} WHERE fromid=:fromid ",
            'cost_materia' => "DELETE FROM {{cost_materia}} WHERE fromid=:fromid ",
            'cost_process' => "DELETE FROM {{cost_process}} WHERE fromid=:fromid ",
            'production' => "DELETE FROM {{production}} WHERE fromid=:fromid ",
            'production_product' => "DELETE FROM {{production_product}} WHERE fromid=:fromid ",
            'production_product_days' => "DELETE FROM {{production_product_days}} WHERE fromid=:fromid ",
            'production_days' => "DELETE FROM {{production_days}} WHERE fromid=:fromid ",
            'production_progresss' => "DELETE FROM {{production_progresss}} WHERE fromid=:fromid ",
        );
        $this->excSqls($sqls);
    }
    private function clearLog() {
        /*成本核算，生产管理*/
        $sqls = array(
            'Admin_Log' => "DELETE FROM {{Admin_Log}} WHERE fromid=:fromid ",
        );
        $this->excSqls($sqls);
    }
    private function clearProduct() {
        /*清空数据,订单*/
        $sqls = array(
            'product_moving' => "DELETE FROM {{product_moving}} WHERE product_id IN (SELECT itemid FROM {{product}} WHERE fromid=:fromid);",
            'product' => "DELETE FROM {{product}} WHERE fromid=:fromid ",
            'stocks' => "DELETE FROM {{stocks}} WHERE fromid=:fromid ",
            'movings' => "DELETE FROM {{movings}} WHERE fromid=:fromid ",
            'category' => "DELETE FROM {{category}} WHERE fromid=:fromid ",
        );
        $this->excSqls($sqls);
    }
    public function actionDatabase() {
        $m = 'live';
        if (isset($_POST[$m]) && isset($_POST[$m]['fid'])) {
            $this->clearProduct($_POST[$m]['fid']);
        }
        $this->render('index', array());
    }
    public function actionWipe() {
    }
    
    public function clearTest() {
        // 47115232649714042
        /*清空数据,订单*/
        $time = $this->ovetime;
        $manageid = 71296373764021601;
        
        $strbaseWhere = ' WHERE fromid=:fromid ';
        $strWhere = $strbaseWhere . 'AND manageid > 47115232649714042';
        $strTimeWhere = $strbaseWhere . "AND add_time >$time ";
        $sqls = array(
            'order_product' => "DELETE FROM {{order_product}} $strbaseWhere AND  order_id IN(SELECT itemid FROM {{order}} $strWhere ) ",
            'order_flow' => "DELETE FROM {{order_flow}} WHERE order_id IN(SELECT itemid FROM {{order}} $strWhere)",
            'order_info' => "DELETE FROM {{order_info}} $strbaseWhere AND   itemid IN(SELECT itemid FROM {{order}} $strWhere ) ",
            
            'order_review' => "DELETE FROM {{order_review}} $strWhere ",
            'order' => "DELETE FROM {{order}} $strWhere ",
            
            'clientele' => "DELETE FROM {{clientele}} $strTimeWhere",
            'contactp_prson' => "DELETE FROM {{contactp_prson}} $strTimeWhere",
            'contact' => "DELETE FROM {{contact}} $strTimeWhere",
            
            'address_book' => "DELETE FROM {{address_book}} $strTimeWhere",
            'address_groups' => "DELETE FROM {{address_groups}} $strTimeWhere",
            
            'manage' => "DELETE FROM {{manage}} $strTimeWhere",
            'events' => "DELETE FROM {{events}} $strTimeWhere",
            'subordinate_mid' => "DELETE FROM {{subordinate}} $strbaseWhere AND mid>$manageid",
            'subordinate_pid' => "DELETE FROM {{subordinate}} $strbaseWhere AND manageid>$manageid",
            
            'rbac_authassignment_itemname' => "DELETE FROM {{rbac_authassignment}} $strbaseWhere  AND itemname>71233233760937294",
            'rbac_authassignment_userid' => "DELETE FROM {{rbac_authassignment}} $strbaseWhere  AND userid>$manageid",
            'rbac_authitemchild' => "DELETE FROM {{rbac_authitemchild}} WHERE parent IN (SELECT name FROM {{rbac_authitem}} WHERE fromid=:fromid AND name>71233233760937294)",
            
            'rbac_authitem' => "DELETE FROM {{rbac_authitem}} $strbaseWhere AND name>71233233760937294",
            
            'category' => "DELETE FROM {{category}} $strbaseWhere AND catid>1408344255",
            
            'cost_product' => "DELETE FROM {{cost_product}} $strbaseWhere AND cost_id IN(SELECT itemid FROM {{cost}}  $strTimeWhere)",
            'cost_materia' => "DELETE FROM {{cost_materia}}  $strbaseWhere  AND cost_id IN(SELECT itemid FROM {{cost}}  $strTimeWhere) ",
            
            'cost' => "DELETE FROM {{cost}} $strTimeWhere",
            'cost_process' => "DELETE FROM {{cost_process}}  $strbaseWhere AND cost_product_id NOT IN(SELECT itemid FROM {{cost_product}}  $strbaseWhere) ",
            
            'production_product' => "DELETE FROM {{production_product}} $strbaseWhere AND production_id IN(SELECT itemid FROM {{production}}  $strTimeWhere) ",
            'production_product_days' => "DELETE FROM {{production_product_days}} $strbaseWhere AND production_id IN(SELECT itemid FROM {{production}}  $strTimeWhere) ",
            'production_days' => "DELETE FROM {{production_days}} $strbaseWhere AND production_id IN(SELECT itemid FROM {{production}}  $strTimeWhere) ",
            'production_progresss' => "DELETE FROM {{production_progresss}} $strbaseWhere AND production_id IN(SELECT itemid FROM {{production}}  $strTimeWhere) ",            
            'production' => "DELETE FROM {{production}} $strTimeWhere ",
            
            'wage' => "DELETE FROM {{wage}} $strTimeWhere",
            'department_worker' => "DELETE FROM {{department_worker}} $strbaseWhere AND department_id  IN(SELECT itemid FROM {{department}}  $strTimeWhere) ",
            'department_price' => "DELETE FROM {{department_price}} $strbaseWhere AND department_id  IN(SELECT itemid FROM {{department}}  $strTimeWhere) ",
            
            'department' => "DELETE FROM {{department}} $strTimeWhere",
        );
        $this->excSqls($sqls);
        /**/
    }
    
    public function actionStocks() {
        $command = Tak::getDb('db')->createCommand('');
        $sql = "SELECT fromid, type , time_stocked,warehouse_id FROM {{product_moving}} WHERE itemid = movings_id GROUP BY time_stocked ORDER BY time_stocked DESC ";
        $sqlAdd = "INSERT INTO {{movings}} (itemid,fromid,warehouse_id,type,time,typeid,enterprise,add_time,note,add_us) VALUES (:itemid,:fromid,:warehouse_id,:type,:time,:stypeid,':
                    enterprise',:add_time,':
                        note',:add_us)";
        $sqlUp = "UPDATE {{product_moving}} SET movings_id=%s WHERE itemid = movings_id AND time_stocked=%s";
        $command->text = $sql;
        $tags = $command->queryAll();
        $itemid = Tak::fastUuid();
        $mod = array();
        foreach ($tags as $key => $value) {
            $itemid = Tak::numAdd($itemid, $key + 2);
            $time = $value['time_stocked'];
            $mod[':itemid'] = $itemid;
            $mod[':fromid'] = $value['fromid'];
            $mod[':warehouse_id'] = $value['warehouse_id'];
            $mod[':stypeid'] = 0;
            $mod[':add_us'] = 0;
            $mod[':type'] = $value['type'];
            $mod[':time_stocked'] = $mod[':time'] = $mod[':add_time'] = $time;
            $mod[':note'] = "初始化导入";
            $mod[':enterprise'] = "导入";
            $command->text = strtr($sqlAdd, $mod);
            // $command->text = $sqlAdd;
            if ($command->execute()) {
                $command->text = sprintf($sqlUp, $itemid, $time);
                $command->execute();
            }
        }
    }
}
