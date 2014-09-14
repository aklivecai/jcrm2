<?php
/**
 *保存表单字段
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 12:09:08
 * @version $Id$
 */

class SettingFormAction extends CAction {
    private $model = null;
    public function run($id) {
        $model = $this->controller->loadModel($id);
        $this->model = $this->controller->loadModels($model->primaryKey, 'FormInfo');
        // Tak::KD($module->attributes,1);
        // Tak::KD($module->attributes,1);
        $this->controller->setLayoutWin();
        if (isset($_POST['content'])) {
            $this->save();
        }
        $this->controller->render('settingform', array(
            'model' => $model,
            'formInfo' => $this->model,
        ));
    }
    public function _render($view, array $options = array()) {
        $this->getController()->render($view, $options);
    }
    private function save() {
        $model = $this->model;
        $itemid = $model->primaryKey;
        $formInfo = FormInfo::model()->findByPk($itemid);
        header('Content-Type: application/json');
        $content = $_POST['content'];
        $mod = new TakDom();
        $files = $mod->getFiles($content);
        $tags = array(
            'info' => '',
            'status' => 1
        );
        
        $list = array();
        $listDel = array();
        $errors = array();
        $info = false;
        //是否有字段需要保存
        if ($files) {
            if (count($files['msgs']) > 0) {
                $info = '表单控件 :';
                $info.= implode(",", $files['msgs']);
                $info.= '有重复,请修改重复的控件名称。';
            } elseif (count($files['files']) == 0) {
                //没有提交字段,删除之前所有字段
                $formInfo->delFields();
            } elseif (count($files['files']) > 0) {
                $_files = $files['files'];
                $cols = $formInfo->getFields();
                foreach ($cols as $key => $value) {
                    if (isset($_files[$value->field_name])) {
                        $value->attributes = $_files[$value->field_name];
                        $list[] = $value;
                        unset($_files[$value->field_name]);
                    } else {
                        $listDel[] = $value;
                    }
                }
                //新增加的
                foreach ($_files as $key => $value) {
                    $m = new FormField('create');
                    $m->attributes = $value;
                    $m->form_id = $itemid;
                    if ($m->validate()) {
                        $list[] = $m;
                    } else {
                        Tak::KD($value);
                        Tak::KD($m->attributes);
                        Tak::KD($m->getErrors());
                        exit;
                        /*错误的不予解析*/
                        $errors[$key] = $m->getErrors();
                    }
                }
            }
        }
        //没有错误,保存html内容到表单中
        if (!$info) {
            $formInfo->forhtml = $content;
            if ($formInfo->save()) {
                $tags['info'] = count($list);
                foreach ($list as $key => $value) {
                    $value->save();
                }
                foreach ($listDel as $value) {
                    $value->delete();
                }
            }
        } else {
            $tags = array(
                'info' => $info,
                'status' => 0,
            );
        }
        $this->getController()->message($tags['info'], $tags['status']);
        // echo CJSON::encode($tags);
        exit;
    }
}
