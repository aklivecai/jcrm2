<?php
class ContactpPrsonController extends Controller {
    public function init() {
        parent::init();
        $this->modelName = 'ContactpPrson';
    }
    protected function getSelectOption($q, $not = false) {
        $result = parent::getSelectOption(false);
        $criteria = $result['data']['criteria'];
        $clienteleid = Tak::getQuery('clienteleid', false);
        if ($q) {
            $criteria->addSearchCondition('nicename', $q, true);
        }
        // Tak::KD($clienteleid);
        if ($clienteleid && (int)$clienteleid >= 0) {
            $criteria->compare('clienteleid', $clienteleid);
        }
        // Tak::KD($result,1);
        $result['data']['criteria'] = $criteria;
        return $result;
    }
}
