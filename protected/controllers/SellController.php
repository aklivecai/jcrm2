<?php
class SellController extends MovingsController {
    
    public function init() {
        $this->type = 2;
        parent::init();
    }
}
