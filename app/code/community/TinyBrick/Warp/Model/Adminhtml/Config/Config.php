<?php

class TinyBrick_Warp_Model_Adminhtml_Config_Config extends Mage_Core_Model_Config_Data
{
    
        public function toOptionArray()
    {
        $result = array();
        $result[] = array(
                'label' => "Disabled",
                'value' => "0"
            );
        $result[] = array(
                'label' => "Enabled",
                'value' => "1"
            );
        
        return $result;
    }
    
}
