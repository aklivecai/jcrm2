<?php
/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-11 16:20:14
 * @version $Id$
 */

class TakCondition {
    public function __construct() {
    }
    public function decide($val, $col, $operation) {
        $result = false;
        switch ($operation) {
            case 'eq':
                $result = $val == $col;
            break;
            case 'gt':
                $result = $col > $val;
            break;
            case 'lt':
                $result = $col < $val;
            break;
            default:
            break;
        }
        return $result;
    }
}
