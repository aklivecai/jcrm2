<?php
class SiteController extends Controller {
    public $layout = 'columnPage';
    public $msg;
    public $model;
    public function filters() {
        return array(
            'rights',
        );
    }
    public function allowedActions() {
        return 'init,index,login,error,ie6,tak,';
    }
    
    protected function beforeRender($view) {
        return true;
        // Tak::KD(Yii::app()->getController()->getAction()->id,1);
        $uage = $_SERVER['HTTP_USER_AGENT'];
        $aid = Yii::app()->getController()->getAction()->id;
        if (($aid != 'ie6') && (strpos($uage, 'MSIE 6.0;') !== false || strpos($uage, 'MSIE 7.0;') !== false)) {
            preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
            $version = 0;
            if (count($matches) > 1) {
                //Then we're using IE
                $version = $matches[1];
            }
            Tak::KD($version);
            Tak::KD($uage, 1);
            $this->redirect(array(
                '/site/ie6'
            ));
        }
        return true;
    }
    
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ) ,
            'page' => array(
                'class' => 'CViewAction',
            ) ,
        );
    }
    
    public function actionSupplier($action = false) {
        if ($action == 'preview') {
            $this->_setLayout('//layouts/column2');
        }
        $this->render('supplier');
    }
    
    public function actionIndex() {
        if (Tak::isGuest()) {
            // Tak::KD($_GET);
            // Tak::KD(current($_GET));
            // Tak::KD(count($_GET));
            // Tak::KD($key);
            $k = key($_GET);
            $itemid = false;
            if ($k) {
                $itemid = Tak::getCryptNum($k);
            }
            if ($itemid) {
                $this->redirect(array(
                    'init',
                    'k' => $k
                ));
            } else {
                $this->errorE();
            }
        }
        $this->_setLayout();
        if (Tak::getSupplier()) {
            # code...
            $this->render('supplier');
        } else {
            $this->render('index');
        }
    }
    
    public function actionIe6() {
        $uage = $_SERVER['HTTP_USER_AGENT'];
        $v = strpos($uage, 'MSIE 6.0;') !== false ? '6' : '7';
        $this->_setLayout('/layouts/columnAjax');
        $this->render('ie6', array(
            'v' => $v
        ));
    }
    
    public function actionHelp() {
        $this->_setLayout();
        $m = 'post';
        // Tak::KD($_POST[$m],1);
        if (isset($_POST[$m]) && $_POST[$m]['name'] && $_POST[$m]['note']) {
            $arr = $_POST[$m];
            $str = <<<END
            <ul>
                <li>平台会员：fromid</li>
                <li>会员ID：manageid(:umane)</li>
                <li>姓名：name</li>
                <li>Email：email</li>
                <li>内容：note</li>
            </ul>
END;
            $str = strtr($str, $arr);
            $arr = Tak::getOM();
            $arr[':umane'] = Tak::getManame();
            $str = strtr($str, $arr);
            $data = array(
                'subject' => '问题反馈',
                'body' => $str,
                'from' => '419524837@qq.com',
                'to' => '419524837@qq.com',
            );
            // Tak::KD($data,1);
            $model = new MailForm();
            $model->attributes = $data;
            if ($model->validate()) {
                $mailer = Yii::app()->mailer;
                $mailer->CharSet = "UTF-8";
                $mailer->IsHTML(true);
                $mailer->IsSMTP();
                $mailer->SMTPAuth = true;
                $mailer->Port = '25';
                $mailer->Host = 'smtp.vip.163.com';
                $mailer->Username = '9juren001';
                $mailer->Password = 'juren001';
                $mailer->From = '9juren001@vip.163.com';
                $mailer->FromName = $model->from;
                $mailer->AddReplyTo($model->from);
                $mailer->AddAddress($model->to);
                $mailer->Subject = ($model->subject);
                $mailer->Body = ($model->body);
                $sendmail = $mailer->Send();
                if ($sendmail) {
                    Tak::setFlash('问题已经反馈到后台，请耐心等待我们的处理!', 'success');
                }
            }
        }
        $this->render('help');
    }
    public function actionWizard() {
        $this->_setLayout();
        $this->render('wizard');
    }
    
    public function actionError() {
        
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) echo $error['message'];
            else $this->render('error', $error);
        } else {
            throw new CHttpException(404, 'Page not found.');
        }
    }
    
    private function inits($k) {
        /*已经登录，返回上一页，没有就首页*/
        if (!Tak::isGuest()) {
            $this->redirect(Yii::app()->user->returnUrl);
        }
        if (!$k) {
            isset(Yii::app()->request->cookies['fid']) && $k = Yii::app()->request->cookies['fid']->value;
            if (!$k && strpos(Yii::app()->user->returnUrl, 'fid')) {
                
                $parts = parse_url(urldecode(Yii::app()->user->returnUrl));
                
                parse_str($parts['query'], $query);
                
                isset($query['fid']) && $k = $query['fid'];
            }
            if ($k) {
                $this->redirect($this->createUrl('login', array(
                    'k' => Tak::setCryptNum($k)
                )));
            }
            /*return $this->inits($k);*/
        } else {
        }
        $itemid = Tak::getCryptNum($k);
        $errorInfo = '会员信息不存在！';
        if ($itemid) {
            $this->msg = $msg = TestMemeber::model()->getMmeber($itemid);
            if (!$msg) {
                $itemid = false;
            } else {
                if ($msg['status'] == 0) {
                    $itemid = false;
                    $errorInfo = '帐号已禁止登录! 请联系客服 400 0168 488';
                } else {
                    $start_time = $msg['start_time'];
                    $active_time = $msg['active_time'];
                    if ($start_time > 0) {
                        if (Tak::isDayOver($active_time, 15)) {
                            $itemid = false;
                            $errorInfo = '帐号已过期! 请联系客服 400 0168 488';
                        } elseif ($this->getAction()->id != 'login') {
                            $this->redirect(array(
                                'login',
                                'k' => $k
                            ));
                        }
                    } else {
                        if ($this->getAction()->id != 'init') {
                            $this->redirect(array(
                                'init',
                                'k' => $k
                            ));
                        }
                    }
                }
            }
        }
        if ($itemid) {
            $itemid = Tak::setCryptKey($itemid);
            return $itemid;
        } else {
            $this->errorE($errorInfo);
        }
    }
    
    private function setting() {
        $list = Setting::model()->getThemes();
        foreach ($list as $key => $value) {
            Yii::app()->user->setState($value->item_key, $value->item_value);
        }
    }
    
    private function setForm($m) {
        $result = isset($_POST[$m]);
        if ($result) {
            $arr = $_POST[$m];
            $fromid = $arr['fromid'];
            if ($fromid) {
                $fromid = Tak::getCryptKey($fromid);
            }
            $arr['fromid'] = $fromid;
            $this->model->attributes = $arr;
            $result = $this->model->validate();
        }
        return $result;
    }
    
    public function actionPreviewTestMember($id) {
        $id = $this->getSId($id);
        $msg = Profile::getOne($id);
        if ($msg === null) throw new CHttpException(404, '所请求的页面不存在。');
        if (!$this->isAjax) $this->_setLayout('//layouts/columnPreview');
        
        $this->render('/profile/_view', array(
            'model' => $msg,
        ));
    }
    
    public function actionInit($k = false) {
        $itemid = $this->inits($k);
        $m = 'InitForm';
        $this->model = new $m();
        if ($this->setForm($m)) {
            if ($this->model->install($this->model->username, $this->model->password)) {
                $model = new LoginForm();
                $model->attributes = $this->model->attributes;
            } else {
                $this->errorE();
            }
            if ($model->login()) {
                $this->setting();
                Yii::app()->user->setFlash('info', '激活成功，建议您先修改密码!');
                $this->redirect(array(
                    'site/changepwd'
                ));
            }
        }
        $this->model->attributes = array(
            'fromid' => $itemid
        );
        $this->render('init', array(
            'model' => $this->model,
            'msg' => $this->msg
        ));
    }
    
    public function actionLogin($k = false) {
        
        $itemid = $this->inits($k);
        $m = 'LoginForm';
        $this->model = $model = new $m();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($this->model);
            Yii::app()->end();
        }
        if ($this->setForm($m) && $model->login()) {
            $this->setting();
            
            if (Yii::app()->user->last_login_time) {
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                Yii::app()->user->setFlash('info', '第一次登录，建议您先修改密码!');
                $this->redirect(array(
                    'site/changepwd'
                ));
            }
        }
        $model->attributes = array(
            'fromid' => $itemid
        );
        $this->render('login', array(
            'model' => $model,
            'msg' => $this->msg
        ));
    }
    
    public function actionLogout() {
        if (Tak::isGuest()) {
            $this->redirect(Yii::app()->user->loginUrl);
        }
        // 更新最后活跃时间
        Manage::model()->upActivkey();
        $fromid = Tak::getFormid();
        if ($fromid == '1') {
            $this->redirect(array(
                '/juren/default/logout'
            ));
        }
        
        $fromid = Tak::setCryptNum($fromid);
        
        Yii::app()->user->logout();
        $this->redirect(array(
            'login',
            'k' => $fromid
        ));
    }
    
    public function actionChangepwd() {
        if (Tak::getFormid() == 3930 && Tak::getManame() == 'admin') {
            $this->error(800, '测试帐号，不允许修改密码！');
        }
        $this->_setLayout();
        $model = new PasswdModifyForm();
        $m = 'PasswdModifyForm';
        $modifySuccess = false;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $model->oldPasswd = "";
                $model->passwd = "";
                $model->passwdConfirm = "";
                Tak::msg('', '修改密码成功！');
            }
        }
        $this->render('changepwd', array(
            'model' => $model,
            'modifySuccess' => $modifySuccess,
        ));
    }
    
    public function actionProfile() {
        $this->_setLayout();
        $m = 'Manage';
        $model = $m::model()->findByPk(Tak::getManageid());
        if (isset($_POST[$m])) {
            $_POST[$m]['user_pass'] = $model->user_pass;
            $_POST[$m]['user_name'] = $model->user_name;
            
            $model->attributes = $_POST[$m];
            
            if ($model->save()) $this->redirect(array(
                'profile'
            ));
        }
        $this->render('profile', array(
            'model' => $model,
        ));
    }
    
    public function actionOrder() {
        $this->_setLayout();
        Yii::app()->user->setFlash('info', '<strong>"订单功能"</strong> 请联系相关业务人员或者 致电400 0168 488');
        $this->render('page');
    }
    
    public function actionAppchace() {
        $this->_setLayout('/layouts/columnAjax');
        $this->render('appcache');
    }
    
    public function actionDatabase() {
        $dtables = $tables = $C = array();
        $i = $j = $dtotalsize = $totalsize = 0;
        $DT_PRE = 'tak_';
        $db_name = 'test';
        // Tak::KD(Yii::app()->db->schema->getTableNames());
        foreach (Yii::app()->db->schema->getTables() as $name => $table) {
        }
        
        $this->_setLayout();
        $this->render('database');
        exit;
        
        $sql = "SHOW TABLES FROM `$db_name`";
        
        $connection = Yii::app()->db;
        
        $tags = $connection->createCommand($sql)->query()->readAll();
        
        Tak::KD($tags, 1);
        
        foreach ($tags as $key => $rr) {
            
            if (!$rr[0]) continue;
            $sql = "SHOW TABLE STATUS FROM `" . $db_name . "` LIKE '" . $rr[0] . "'";
            $r = $connection->createCommand($sql)->query()->readAll();
            
            if (preg_match('/^' . $DT_PRE . '/', $rr[0])) {
                $dtables[$i]['name'] = $r['Name'];
                $dtables[$i]['rows'] = $r['Rows'];
                $dtables[$i]['size'] = round($r['Data_length'] / 1024 / 1024, 2);
                $dtables[$i]['index'] = round($r['Index_length'] / 1024 / 1024, 2);
                $dtables[$i]['tsize'] = $dtables[$i]['size'] + $dtables[$i]['index'];
                $dtables[$i]['auto'] = $r['Auto_increment'];
                $dtables[$i]['updatetime'] = $r['Update_time'];
                $dtables[$i]['note'] = $r['Comment'];
                $dtables[$i]['chip'] = $r['Data_free'];
                $dtotalsize+= $r['Data_length'] + $r['Index_length'];
                $C[str_replace($DT_PRE, '', $r['Name']) ] = $r['Comment'];
                $i++;
            } else {
                $tables[$j]['name'] = $r['Name'];
                $tables[$j]['rows'] = $r['Rows'];
                $tables[$j]['size'] = round($r['Data_length'] / 1024 / 1024, 2);
                $tables[$j]['index'] = round($r['Index_length'] / 1024 / 1024, 2);
                $tables[$j]['tsize'] = $tables[$j]['size'] + $tables[$j]['index'];
                $tables[$j]['auto'] = $r['Auto_increment'];
                $tables[$j]['updatetime'] = $r['Update_time'];
                $tables[$j]['note'] = $r['Comment'];
                $tables[$j]['chip'] = $r['Data_free'];
                $totalsize+= $r['Data_length'] + $r['Index_length'];
                $j++;
            }
        }
        $dtotalsize = round($dtotalsize / 1024 / 1024, 2);
        $totalsize = round($totalsize / 1024 / 1024, 2);
    }
    /**
     * 还原数据库
     */
    public function actionRestores() {
        $db = Yii::app()->getDb();
        $schema = file_get_contents(Yii::app()->basePath . '/data/reset.sql');
        $schema = preg_split("/;\s+/", trim($schema, ';'));
        foreach ($schema as $sql) $db->createCommand($sql)->execute();
        Yii::app()->user->setFlash('success', 'Database has been restored!');
        $this->redirect(Yii::app()->homeUrl);
    }
    public function actionTak() {
        $this->redirect(array(
            '/juren/company/'
        ));
    }
}
