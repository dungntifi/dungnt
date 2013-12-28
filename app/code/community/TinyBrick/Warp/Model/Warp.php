<?php

class TinyBrick_Warp_Model_Warp extends Mage_Core_Model_Abstract
{
    /**
     * Initialize object
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('warp/warp');
    }
}