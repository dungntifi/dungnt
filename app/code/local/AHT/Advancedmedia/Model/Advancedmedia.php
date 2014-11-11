<?php

class AHT_Advancedmedia_Model_Advancedmedia extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advancedmedia/advancedmedia');
    }
}