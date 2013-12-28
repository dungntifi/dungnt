<?php

class TinyBrick_Warp_Model_Adminhtml_Config_Version extends Mage_Core_Model_Config_Data
{
    
        public function toOptionArray()
    {
        $result = array();
        if(Mage::helper('warp')->_isStandard() == true){
	        $result[] = array(
	                'label' => "Standard",
	                'value' => "1"
	            );
        }
        if(Mage::helper('warp')->_isStandard() != true){
	        $result[] = array(
	                'label' => "Advanced",
	                'value' => "2"
	            );
        }
        return $result;
    }
    
}
