<?php
class Jain_Ordertracking_Block_Ordertracking extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getOrdertracking()     
     { 
        if (!$this->hasData('ordertracking')) {
            $this->setData('ordertracking', Mage::registry('ordertracking'));
        }
        return $this->getData('ordertracking');
        
    }
}