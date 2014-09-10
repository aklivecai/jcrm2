<?php
class InfoForm extends CFormModel {
    public $proj_id;
    public $describe;
    public $user;
    public $time;
    public $aimage;
    public function rules() {
        return array(
            //  // name, email, subject and body are required
            array(
                'proj_id, describe, user, time',
                'required'
            ) ,
            array(
                'proj_id',
                'numerical'
            ) ,
            array(
                'aimage',
                'file',
                'types' => 'jpg, png, jpeg, ',
                'allowEmpty' => true
            ) ,
        );
    }
}
?>