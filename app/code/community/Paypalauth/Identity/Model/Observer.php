<?php

class Paypalauth_Identity_Model_Observer{
    /**
     * Save system config event 
     *
     * @param Varien_Object $observer
     */
    public function saveSystemConfig($observer)
    {
        $store = $observer->getStore();
        $website = $observer->getWebsite();

        if (substr((string)Mage::getConfig()->getNode('default/web/secure/base_url'),0,5) !== 'https') {
            $groups['startup']['fields']['paypalauth_enabled']['value'] = 0;
            Mage::getModel('adminhtml/config_data')
                    ->setSection('customer')
                    ->setWebsite($website)
                    ->setStore($store)
                    ->setGroups($groups)
                    ->save();
        }
    }
    
    protected function _getSession(){
    	return Mage::getSingleton('checkout/session');
    }
    
   
    
    /** AJAX CART EXTENSION **/
    
    /**
     * EVENT WHEN PRODUCT HAS BEEN ADDED TO SHOPPING CART
     */
    public function checkoutCartAddProductComplete($observer){
   	
    	$request = $observer->getData('request');
    	$response = $observer->getData('response');
    
    	$isAjaxModule = $request->getParam('ajax', false);
    
    	if ($isAjaxModule){
    
    		$responseData = array();
    		try{    			
    			$responseData['error'] = false;    			
    		}catch(Exception $e){
    			$responseData['error'] = true;
    			$responseData['error'] = $e->getMessage();    
    		}
    
    		Mage::getSingleton('checkout/session')->setNoCartRedirect(true);
    			
    		$response->setHeader('Content-type','application/json', true);
    		$response->setBody(Mage::helper('core')->jsonEncode($responseData));
    	
    		
    	}
    }
}
