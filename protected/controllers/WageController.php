<?php
/**
 *  工资
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-27 09:31:25
 * @version $Id$
 */
class WageController extends Controller {
    public $modelName = 'Wage';
    public function init() {
        parent::init();
    }
    public function getData() {
        $result = Workshop::getAll();
        foreach ($result as $key => $value) {
            $result[$key]['workers'] = array(
                array(
                    'name' => '张三',
                    'itemid' => '1',
                ) ,
                array(
                    'name' => '李四',
                    'itemid' => '2',
                ) ,
                array(
                    'name' => '王五',
                    'itemid' => '3',
                ) ,
            );
        }
        return $result;
    }
    public function actionIndex() {
        $m = $this->modelName;
        $model = new $m('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$m])) {
            $model->attributes = $_GET[$m];
        }
        // $str = implode(',',array_keys($model->attributeLabels()));
        // Tak::KD($str);
        $this->render($this->templates['index'], array(
            'model' => $model,
        ));
    }
    // http://test.9juren.com/Wage/Workshop
    public function actionWorkshop() {
        $data = $this->getData();
        $this->render('workshop', array(
            'data' => $data,
        ));
    }
    public function actionAdmin() {
    }
    public function actionCount($page = 0) {
        $page = (int)$page;
        $sm = 'SearchWageForm';
        $search = new $sm('search');
        $search->unsetAttributes();
        $listY = $search->getYeas();
        if (isset($_GET[$sm])) {
            $search->attributes = $_GET[$sm];
            if ($search->keyword) {
                $search->keyword = addslashes($search->keyword);
            }
        }
        $m = $this->modelName;
        $model = new $m('search');
        $data = $model->getData($search->yea_v, $search->keyword, $page, 20);
        
        if ($model->totals > 0) {
            $criteria = new CDbCriteria();
            $criteria->addCondition('name', $search->keyword);
            $criteria->addCondition('yea', $search->yea);
            $pages = new CPagination($model->totals);
            $pages->pageSize = 20;
            $pages->applyLimit($criteria);
        } else {
            $pages = null;
        }
        
        $this->render('count', array(
            'data' => $data,
            'listY' => $listY,
            'search' => $search,
            'pages' => $pages,
        ));
    }
    public function actionCreate() {
        $m = 'M';
        if (isset($_POST[$m]) && is_array($_POST[$m])) {
            $datas = $_POST[$m];
            $m = $this->modelName;
            $model = new $m('create');
            $itemid = 0;
            $errors = array();
            foreach ($datas as $key => $value) {
                $value['process_id'] = $this->getSId($value['process_id']);
                $value['worker_id'] = $this->getSId($value['worker_id']);
                $model->attributes = $value;
                if ($model->itemid) {
                    $itemid = Tak::numAdd($model->itemid, 2);
                    $model->setIsNewRecord(true);
                    $model->itemid = $itemid;
                }
                if ($model->validate() && $model->save()) {
                } else {
                    $errors[] = $model->getErrors();
                }
            }
            if (count($errors) > 0) {
                // Tak::KD($errors);
                
            }
            $this->redirect(array(
                'index',
                $m . '[add_time]' => $model->add_time,
                $m . '[add_ip]' => $model->add_ip,
            ));
        }
        $this->render('create', array(
            'model' => $model
        ));
    }
}
