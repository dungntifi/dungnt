<?php
class Magik_Pricedropalert_Model_Mysql4_Pricedropalert extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("pricedropalert/pricedropalert", "id");
    }
}