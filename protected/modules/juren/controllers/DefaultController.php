<?php
class DefaultController extends JController {
    
    public function init() {
        parent::init();
        $this->layout = 'column1';
        if (!Tak::isGuest()) {
            $this->checkAccess();
        }
    }
    public function allowedActions() {
        return 'login, error';
    }
    
    public function actionIndex() {
        $this->render('index');
    }
    /*检测权限*/
    private function checkAccess() {
        $uid = Tak::getManageid();
        $sql = "SELECT itemname FROM {{rbac_authassignment}}  WHERE fromid = 1 AND userid=$uid ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
        $reader = $command->query();
        $auth = Yii::app()->authManager;
        if (Tak::checkSuperuser()) {
        }
    }
    /*发送邮件*/
    public function actionEmail($email = '', $itemid = '') {
        
        $model = new MailForm();
        $items = array();
        $msg = array();
        if ($itemid > 0) {
            $msg = Test9Memeber::model()->findByPk($itemid);
            if ($msg != null) {
                $model->to = $msg->email;
                $model->from = Manage::model()->findByPk(Tak::getManageid())->user_nicename;
            }
        }
        
        if (isset($_POST["MailForm"])) {
            $model->attributes = $_POST['MailForm'];
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
                // $mailer->Host = 'smtp.126.com';
                // $mailer->Username = 'z01926';
                // $mailer->Password = 'cb19880627';
                // $mailer->From = 'z01926@126.com';
                
                $mailer->FromName = $model->from;
                $mailer->AddReplyTo($model->from);
                $mailer->AddAddress($model->to);
                $mailer->Subject = ($model->subject);
                $mailer->Body = ($model->body);
                // Tak::KD($model->body);
                $sendmail = $mailer->Send();
                if ($sendmail) {
                    Tak::setState('esubject', $model->subject);
                    
                    $nex = $msg->getPrevious(false);
                    if ($nex) {
                        Tak::setFlash("邮件发送成功! \n<br />下一个..", "success");
                        $this->redirect(array(
                            'email',
                            'itemid' => $nex['itemid']
                        ));
                    } else {
                        Tak::setFlash("邮件发送成功! \n<br />没有下一个..", "success");
                        $this->refresh();
                    }
                } else {
                    Tak::setFlash("邮件发送失败！ \n", "failed");
                }
            }
        }
        
        $model->subject = Tak::getState('esubject', '');
        
        $this->render('email', array(
            'model' => $model,
            'msg' => $msg,
        ));
    }
    /*登录系统*/
    public function actionLogin($itemid = false) {
        /*已经登录，返回上一页，没有就首页*/
        if (!Tak::isGuest()) {
            $this->redirect(Yii::app()->user->returnUrl);
        }
        
        $this->layout = 'column1';
        $model = new LoginForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            $_POST['LoginForm']['fromid'] = 1;
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $fromid = $_POST['LoginForm']['fromid'];
            if ($fromid) {
                $fromid = Tak::getCryptKey($fromid);
            } else {
                $fromid = 1;
            }
            $fromid = 1;
            $_POST['LoginForm']['fromid'] = $fromid;
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $this->redirect(array(
                    'index'
                ));
            }
        }
        if ($itemid) {
            $model->fromid = Tak::getCryptKey($itemid);
        } else {
            $itemid = Tak::setCryptKey(1);
        }
        $model->attributes = array(
            'fromid' => $itemid
        );
        // display the login form
        $this->render('login', array(
            'model' => $model
        ));
    }
    /*退出登录*/
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(array(
            'default/login'
        ));
    }
    //修改密码
    public function actionChangepwd() {
        $model = new PasswdModifyForm();
        $m = 'PasswdModifyForm';
        $modifySuccess = false;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->save()) {
                $model->oldPasswd = "";
                $model->passwd = "";
                $model->passwdConfirm = "";
            }
        }
        $this->render('changepwd', array(
            'model' => $model,
            'modifySuccess' => $modifySuccess,
        ));
    }
}
