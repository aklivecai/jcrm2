<?php
class MovesController extends Controller {
    public $defaultAction = 'index';
    public function init() {
        parent::init();
    }
    
    public function actionClienteles() {
        $m = 'MovesForm';
        $model = new $m;
        if (isset($_POST[$m])) {
            $model->attributes = $_POST[$m];
            if ($model->validate()) {
                $arr = $model->moveClienteles();
                if ($arr && count($arr) > 0) {
                    $str = '成功转移 <br />客户 <span class="red">:c</span> ,<br /> 联系人<span class="red">:cp</span>, <br />联系记录<span class="red">:cc</span>';
                    $str = strtr($str, $arr);
                    Tak::msg('', $str);
                }
            }
        }
        $this->render('clienteles', array(
            'model' => $model,
        ));
    }
}
