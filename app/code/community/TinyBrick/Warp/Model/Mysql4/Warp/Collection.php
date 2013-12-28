<?php

class TinyBrick_Warp_Model_Mysql4_Warp_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('warp/warp');
    }
}