<?php   
class Magik_Pricedropalert_Block_Pricedropalert extends Mage_Core_Block_Template{   

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getPricedeopAlert()     
     { 
        if (!$this->hasData('pricedropalert')) {
            $this->setData('pricedropalert', Mage::registry('pricedropalert'));
        }
        return $this->getData('pricedropalert');
        
    }



}