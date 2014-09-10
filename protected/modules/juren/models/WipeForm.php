<?php
class WipeForm extends CFormModel {
    public $fromid ;
    public $sqls = array(
        'DELETE FROM {{admin_log}} WHERE fromid =:fromid  ',
        'DELETE FROM  {{events}} WHERE fromid =:fromid   ',
        'DELETE FROM {{type}} WHERE fromid =:fromid  ',
        'DELETE FROM {{address_book}} WHERE fromid =:fromid  ',
        // 'DELETE FROM {{files}} WHERE fromid =:fromid  ',
        // 'DELETE FROM {{files_share}} WHERE fromid =:fromid  ',
        // 'DELETE FROM {{files_star}} WHERE fromid =:fromid  ',
        'DELETE FROM {{warehouse}} WHERE fromid =:fromid  ',
        // 'DELETE FROM {{subordinate}} WHERE fromid =:fromid  ',        

        'DELETE pm FROM {{product_moving}} AS pm LEFT JOIN {{product}} AS p ON pm.product_id=p.itemid WHERE p.fromid=:fromid  ',

        'DELETE FROM {{movings}} WHERE fromid =:fromid  ',
        'DELETE FROM {{stocks}} WHERE fromid =:fromid   ',
        'DELETE FROM {{product}} WHERE fromid =:fromid  ',
        'DELETE f FROM {{order_files}} AS f LEFT JOIN {{order_product}} AS p ON f.itemid=p.itemid WHERE p.fromid=:fromid  ',
        'DELETE f FROM {{order_files}} AS f LEFT JOIN {{order_flow}} AS fl ON f.itemid=fl.itemid  LEFT JOIN {{order}} AS o ON fl.order_id = o.itemid WHERE o.fromid=:fromid  ',
        'DELETE FROM {{order_product}} WHERE fromid =:fromid   ',
        'DELETE i FROM {{order_info}} AS i LEFT JOIN {{order}} AS o ON i.itemid=o.itemid WHERE o.fromid =:fromid  ',
        'DELETE FROM {{order}} WHERE fromid =:fromid',
        'DELETE FROM {{contact}}  WHERE fromid =:fromid',
        'DELETE FROM {{contactp_prson}}  WHERE fromid =:fromid',
        'DELETE FROM  {{clientele}} WHERE fromid =:fromid',
    );
    public function rules() {
        return array(
            array(
                'fromid',
                'required'
            ) ,
            array(
                'fromid',
                'numerical',
                'integerOnly' => true
            ) ,
        );
    }
    public function attributeLabels() {
        return array(
            'fromid' => "会员",
        );
    }
}
