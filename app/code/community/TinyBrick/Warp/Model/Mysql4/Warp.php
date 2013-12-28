<?php

class TinyBrick_Warp_Model_Mysql4_Warp extends Mage_Core_Model_Mysql4_Abstract
{
    
     /**
     * Initialize object
     *
     * @return void
     */
    public function _construct()
    {   
        $this->_init('warp/warp', 'id');
    }
}