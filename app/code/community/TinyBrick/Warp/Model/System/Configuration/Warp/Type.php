<?php

class TinyBrick_Warp_Model_System_Configuration_Warp_Type extends Mage_Core_Model_Config_Data
{
    
    public function toOptionArray()
    {
        $result = array();
        $result[] = array(
                'label' => 'files',
                'value' => 'files'
        );
        $result[] = array(
                'label' => 'memcached',
                'value' => 'memcached'
        );
        if(Mage::helper('warp')->_isStandard() != true){
	        $result[] = array(
	                'label' => 'redis',
	                'value' => 'redis'
	        );
        }
        return $result;
    }
    
}