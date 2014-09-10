<?php
/**
 * 打印
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-20 07:10:27
 * @version $Id$
 */

class PrintfController extends Controller {
    public function init() {
        parent::init();
    }
    /**
     * 送货单
     * @return [type] [description]
     */
    public function actionDelivery() {
        $this->render('delivery', array(
            'model' => $model,
        ));
    }
    public function actionSubmit() {
        $m = 'm';
        if (isset($_POST[$m]) && is_array($_POST[$m])) {
            Tak::setFCache('print_data', $_POST[$m]);
        }
        $day = date('Ymd');
        $id = Tak::getFCache($day . 'no_id');
        $id++;
        Tak::setFCache($day . 'no_id', $id);
        Tak::KD($id);
    }
    public function actionPrintfDelivery() {
        $m = 'M';
        $pdata = Tak::getFCache('print_data');
        if (!$pdata) {
            $pdata = array(
                'address' => '地址：广东省深圳市龙岗区坪地泰宝路18号',
                'tel' => '电话：0755-84093301  传真：0755-84093262',
                'p1' => '农行帐号：6228 4501 2801 1534 273    开户行：农行深圳市龙岗分行坪山支行    户名：黄水生',
                'p2' => '工行帐号：622 2024 0000 7773 5120    开户行：中国工商银行龙岗分行坪地支行  户名：黄水生 ',
            );
        }
        if (isset($_POST[$m]) && is_array($_POST[$m])) {
            $_tags = $_POST[$m];
            $tags = array();
            $totals = 0;
            foreach ($_tags as $value) {
                $tags[] = $value;
                $totals+= $value['sum'];
            }
            $totalA = Tak::toCNcap($totals);
            $day = date('Ymd');
            $id = Tak::getFCache($day . 'no_id');
            if (!$id) {
                $id = $day . '0001';
                Tak::setFCache($day . 'no_id', $id);
            }
            $model = array(
                'totals' => $totals,
                'totalA' => $totalA,
                'company' => $_POST['company'],
                'no' => $id,
            );
            $data = array(
                'tags' => $tags,
                'model' => $model
            );
            Tak::setFCache('PrintfDelivery', $data);
        } else {
            $datas = Tak::getFCache('PrintfDelivery');
            if (!$datas) {
                $this->error();
            }
            extract($datas);
        }
        $this->layout = '//layouts/colummPrint';
        $fid = Tak::getFormid();
        $company = TestMemeber::model()->findByPk($fid);
        $this->render('delivery_printf', array(
            'company' => $company,
            'tags' => $tags,
            'model' => $model,
            'pdata' => $pdata,
        ));
    }
}
