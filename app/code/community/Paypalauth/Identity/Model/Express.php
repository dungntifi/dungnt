<?php
class Paypalauth_Identity_Model_Express extends Mage_Paypal_Model_Express{


    /**
     * Checkout redirect URL getter for onepage checkout (hardcode)
     *
     * @see Mage_Checkout_OnepageController::savePaymentAction()
     * @see Mage_Sales_Model_Quote_Payment::getCheckoutRedirectUrl()
     * @return string
     */
    public function getCheckoutRedirectUrl()
    {
    	if(Mage::getStoreConfig('payment/paypallightbox/enable')){
        	return Mage::getUrl('login/express/start');
    	}
    	
    	return parent::getCheckoutRedirectUrl();
    }

    
}
