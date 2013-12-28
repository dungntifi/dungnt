<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Ogrid
*/
class Amasty_Scheckout_Model_Observer 
{
    protected $_location;
    protected function getRemoteAddr(){
        
        $addr = Mage::helper('core/http')->getRemoteAddr(true);
//        $addr = ip2long('208.122.53.131');
        
//        $addr = ip2long('213.184.225.37'); //MINSK
//        $addr = ip2long('195.170.32.19'); //MOSKOW
//        $addr = ip2long('197.157.244.0'); //somalia
        return $addr;
    }
    
    protected function getDefaultCountry(){
        $ret = NULL;
        
        if (Mage::getModel('amscheckout/import')->isDone() && Mage::getStoreConfig('amscheckout/geoip/use') == 1){
            $longIP = $this->getRemoteAddr();
            
            $country = Mage::getModel('amscheckout/country');
            
            $countryCollection = $country->getCollection();
            
            $countryCollection->getSelect()->where("$longIP between ip_from and ip_to");
            
            $data = $countryCollection->getData();
            if (count($data) > 0)
                $ret = $data[0]['code'];
        }
        
        
        if (empty($ret)){
            $ret = Mage::getStoreConfig('amscheckout/default/country');
        }
        
        if (empty($ret)){
            $ret = Mage::getStoreConfig('general/country/default');
        }

        return $ret;
    }
    
    protected function getGeipLocation(){
        if (!$this->_location) {
            $longIP = $this->getRemoteAddr();
            
            $block = Mage::getModel('amscheckout/block');
            
            $blockCollection = $block->getCollection();
            
            $blockCollection->getSelect()->join(
                    array(
                        'locations' => Mage::getSingleton('core/resource')->getTableName('amscheckout/location')
                    ), 'locations.geoip_loc_id = main_table.geoip_loc_id', 
                    array('locations.city', 'locations.postal_code'));
            
            $blockCollection->getSelect()->where("$longIP between main_table.start_ip_num and main_table.end_ip_num");
            
            $data = $blockCollection->getData();

            if (count($data) > 0)
                $this->_location = $data[0];
        }
        return $this->_location;
    }
    
    protected function getDefaultCity(){
        $ret = NULL;
        
        if (Mage::getModel('amscheckout/import')->isDone() && Mage::getStoreConfig('amscheckout/geoip/use') == 1){
            $location = $this->getGeipLocation();
        
            $ret = $location['city'];
        }
        
        return $ret;
    }
    
    protected function getDefaultPostcode(){
        if (Mage::getModel('amscheckout/import')->isDone() && Mage::getStoreConfig('amscheckout/geoip/use') == 1){
            $location = $this->getGeipLocation();
            
            $ret = $location['postal_code'];
        }
        
        return $ret;
    }
    
    public function onControllerActionPredispatch($observer){
       if($observer->getEvent()->getControllerAction()->getFullActionName() == 'checkout_onepage_index' ||
               $observer->getEvent()->getControllerAction()->getFullActionName() == 'checkout_index_index')
        {
            $byDefault = $observer->getControllerAction()->getRequest()->getParam('default', false);
            
            if (!$byDefault){
                
               $observer->getControllerAction()->getResponse()->setRedirect(Mage::getUrl('amscheckout/onepage', array('_secure'=>true)));
            }
        }
    }
    
    protected function resetAddress(&$address){
        
        if (!$address->getCity()){
            $address->setCity($this->getDefaultCity());
        }
        
        if (!$address->getCountryId()){
            $address->setCountryId($this->getDefaultCountry());
        }
        
        if (!$address->getPostcode()){
            $address->setPostcode($this->getDefaultPostcode());
        }
    }
    
     public function onControllerActionLayoutRenderBeforeCheckoutOnepageIndex($observer){
        
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        
        $helper = Mage::helper('amscheckout');
        $billingBlock = Mage::getSingleton('core/layout')->getBlock('checkout.onepage.billing');
        
                
        
        if ($billingBlock){
            $quote = Mage::getSingleton('checkout/type_onepage')->getQuote();
            $billingAddress = $billingBlock->getAddress();
//            $billingAddress = $quote->getBillingAddress();

            $this->resetAddress($billingAddress);

            $shippingAddress = Mage::getSingleton('core/layout')->getBlock('checkout.onepage.shipping')->getAddress();

            $this->resetAddress($shippingAddress);

            $shippingMethodAddress = Mage::getSingleton('core/layout')->getBlock('checkout.onepage.shipping_method.available')->getAddress();

            $currentShippingMethod = $shippingMethodAddress->getShippingMethod();

            if (empty($currentShippingMethod)){
//                $quote = Mage::getSingleton('checkout/type_onepage')->getQuote();
                $quote->collectTotals();
                $quote->save();

                $shippingMethod = Mage::getStoreConfig('amscheckout/default/shipping_method');

                if (empty($shippingMethod))
                    $shippingMethod = $helper->getDefaultShippingMethod();

    //            $res = Mage::getSingleton('checkout/type_onepage')->saveShippingMethod($shippingMethod);
    //            Mage::getSingleton('checkout/type_onepage')->getQuote()->collectTotals()->save();


                $shippingMethodAddress->setShippingMethod($shippingMethod);
            }
        
            $payment = Mage::getSingleton('core/layout')->getBlock('checkout.onepage.payment')->getQuote()->getPayment();
            $currentPaymentMethod = $payment->getMethod();
        
            if (empty($currentPaymentMethod)){
            
                $paymentMethod = Mage::getStoreConfig('amscheckout/default/payment_method');
                
                if (empty($paymentMethod))
                    $paymentMethod = $helper->getDefaultPeymentMethod();

//                $quote = Mage::getSingleton('core/layout')->getBlock('checkout.onepage.payment')->getQuote();

                $payment = $quote->getPayment();

                $payment->setMethod($paymentMethod);
                try {
                    $method = $payment->getMethodInstance();
                    $quote->save();   
                } catch (Exception $e){

                }
            }
            
            
//            $quote = Mage::getSingleton('checkout/type_onepage')->getQuote();

            $quote->getBillingAddress();
            $quote->getShippingAddress()
                ->setCountryId($shippingAddress->getCountryId())
                ->setCollectShippingRates(true);

            $quote->setTotalsCollectedFlag(false);

            $quote->collectTotals();
            $quote->save();

            Mage::getSingleton('customer/session')->setAfterAuthUrl(Mage::getUrl('amscheckout/onepage/index'));
            Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('amscheckout/onepage/index'));

        }
    }
    
    public function onControllerActionLayoutGenerateBlocksAfter($observer){
        if (
            Mage::app()->getFrontController()->getRequest()->getModuleName() == 'checkout' && 
            Mage::app()->getFrontController()->getRequest()->getRequestedControllerName() == 'cart' && 
            Mage::app()->getFrontController()->getRequest()->getRequestedActionName() == 'index'
        ){
            $shoppingCard = Mage::getStoreConfig('amscheckout/shopping_cart/checkout');
            
            if ($shoppingCard == 1){
                $storageName = 'customer/session';
                
                $storage = Mage::getSingleton($storageName);
                
                if ($storage) {
                    $block = Mage::getSingleton('core/layout')->getMessagesBlock();
                    $block->addMessages($storage->getMessages(true));
                    $block->setEscapeMessageFlag($storage->getEscapeMessages(true));
//                    $block->addStorageType($storageName);
                }
                
//                Mage::app()->getFrontController()->getAction()->initLayoutMessages('customer/session');
            }
        }
    }
    
    public function onControllerActionLoadLayoutBefore($observer){
        
        if (
            Mage::app()->getFrontController()->getRequest()->getModuleName() == 'checkout' && 
            Mage::app()->getFrontController()->getRequest()->getRequestedControllerName() == 'cart' && 
            Mage::app()->getFrontController()->getRequest()->getRequestedActionName() == 'index'
        ){
            
            $shoppingCard = Mage::getStoreConfig('amscheckout/shopping_cart/checkout');
            
            $quote = Mage::getSingleton('checkout/type_onepage')->getQuote();
            if ($quote->hasItems()  && !$quote->getHasError() && $shoppingCard == 1) {
                
                $layout = $observer->getEvent()->getLayout();
                
                
                $update = $layout->getUpdate();

                $update->addHandle('amscheckout_onepage_static');

                $update->addHandle('amscheckout_onepage_content');

                $update->addUpdate("<remove name=\"checkout.cart.top_methods\"/>");
                $update->addUpdate("<remove name=\"checkout.cart.methods\"/>");
                $update->addUpdate("<remove name=\"checkout.cart.totals\"/>");
                $update->addUpdate("<remove name=\"checkout.cart.shipping\"/>");
            }
                    
            
        }

        return $this;
    }
    
}
?>