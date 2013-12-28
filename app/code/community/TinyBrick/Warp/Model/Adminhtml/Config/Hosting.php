<?php

class TinyBrick_Warp_Model_Adminhtml_Config_Hosting extends Mage_Core_Model_Config_Data
{
    
        public function toOptionArray()
    {
        $result = array();
        $result[] = array(
                'label' => "Hosting",
                'value' => "0"
            );       
        return $result;
    }
    
}
