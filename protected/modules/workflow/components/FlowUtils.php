<?php
/**
 *流程操作类
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-10 18:23:45
 * @version $Id$
 */

class FlowUtils extends CComponent {
    private $flowInfo = null;
    private $fowForm = null;
    public function __construct($scenario = 'insert') {
        $this->init();
    }
    public function init() {
        $this->raiseEvent('onClicked', array());
    }
    public function addEvent() {
        $this->raiseEvent('onClicked', array());
    }
    public function onClicked($event) {
        $this->raiseEvent('onClicked', $event);
    }
}
