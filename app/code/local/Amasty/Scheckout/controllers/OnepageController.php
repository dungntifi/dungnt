<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Scheckout
*/
require_once Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'OnepageController.php';

class Amasty_Scheckout_OnepageController extends Mage_Checkout_OnepageController
{
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }
    
    public function indexAction()
    {
        if (!Mage::helper('checkout')->canOnepageCheckout()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        
        $quote = $this->getOnepage()->getQuote();
        
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
                Mage::getStoreConfig('sales/minimum_order/error_message') :
                Mage::helper('checkout')->__('Subtotal must exceed minimum order amount');

            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
//        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
        $this->getOnepage()->initCheckout();
        
        $result = $this->getOnepage()->saveCheckoutMethod('guest');

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }
    
    public function ajaxAction(){
        
        
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        
        $helper = Mage::helper('amscheckout');

//        Mage::getSingleton('customer/session')->setBeforeAuthUrl($_SERVER['HTTP_REFERER']);
    
        $data = $this->getRequest()->getPost('billing', array());
        $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

        if (isset($data['email'])) {
            $data['email'] = trim($data['email']);
        }

        $result = $this->getOnepage()->saveBilling($data, $customerAddressId);
        
        
        $shippingMethod = $this->getOnepage()->getQuote()->getShippingAddress()
            ->getShippingMethod();
        
        $paymentMethod = $this->getOnepage()->getQuote()->getPayment()->getMethod();
        
         
//        if (empty($shippingMethod)){
            $res = $this->getOnepage()->saveShippingMethod($shippingMethod);

            $this->getOnepage()->getQuote()->collectTotals()->save();
//        }
            
       
        
//        $this->getOnepage()->savePayment(array(
//            'method' => $paymentMethod
//        ));
            
        
//        $this->getOnepage()->savePayment(array(
//            'method' => $helper->getDefaultPeymentMethod()
//        ));
    }
    
    public function savePaymentAction(){
//        $data = $this->getRequest()->getPost('payment', array());
//
//        
//        $onepage = Mage::getSingleton('checkout/type_onepage');
//        
//        $quote = $onepage->getQuote();
//        
//        $data = new Varien_Object($data);
//            
//        $payment = $quote->getPayment();
//        
//        $payment->setMethod($data->getMethod());
//        $method = $payment->getMethodInstance();
//        $method->assignData($data);
//        
//        $quote->save();
//        
//        $this->loadLayout('checkout_onepage_review');
//        
//        $result['goto_section'] = 'review';
//        $result['update_section'] = array(
//            'name' => 'review',
//            'html' => $this->_getReviewHtml()
//        );

        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }
        
            // set payment to quote
            $result = array();
            $data = $this->getRequest()->getPost('payment', array());
            $result = $this->getOnepage()->savePayment($data);
        
            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
        $this->loadLayout('checkout_onepage_review');
        $result['goto_section'] = 'review';
        $result['update_section'] = array(
            'name' => 'review',
            'html' => $this->_getReviewHtml()
        );
            }
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
        } catch (Exception $e) {
            $data = $this->getRequest()->getPost('payment', array());


            $onepage = Mage::getSingleton('checkout/type_onepage');

            $quote = $onepage->getQuote();

            $data = new Varien_Object($data);

            $payment = $quote->getPayment();

            $payment->setMethod($data->getMethod());
            $method = $payment->getMethodInstance();
            $method->assignData($data);

            $quote->save();
            
            $this->loadLayout('checkout_onepage_review');
            $result['goto_section'] = 'review';
            $result['update_section'] = array(
                'name' => 'review',
                'html' => $this->_getReviewHtml()
            );
//            Mage::logException($e);
//            $result['error'] = $this->__('Unable to set Payment Method.');
        }
        
        $shoppingCard = Mage::getStoreConfig('amscheckout/shopping_cart/checkout');
        
        if ($shoppingCard == 1){
           $result['update_section']['checkout_cart']  = $this->_getCheckoutCartHtml();
        }
        
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                
    }
    
    public function saveDefaultsAction(){
        $quote = $this->getOnepage()->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethodChanged = FALSE;
        $paymentMethodChanged = FALSE;
        
        if (!$shippingAddress->getShippingMethod()){
            $shippingMethod = NULL;
            $shippingAddress->collectShippingRates()->save();
            
            $groups = $shippingAddress->getGroupedAllShippingRates();
            
            foreach($groups as $code => $_rates){
                foreach ($_rates as $_rate){
                    $shippingMethod = $_rate->getCode();
                    break;
                }
            }
            
            $shippingAddress->setShippingMethod($shippingMethod);
            $shippingMethodChanged = TRUE;
        }
        
        $payment = $quote->getPayment();
        
        if (!$payment->getMethod()){
            $methods = $this->_getAvailablePaymentMethods($quote);
            $paymentMethod = NULL;
            
            if (isset($methods[0])){
                $paymentMethod = $methods[0]->getCode();
            
                $payment = $quote->getPayment();
                $payment->setMethod($paymentMethod);
                $method = $payment->getMethodInstance();
                $quote->save();
                $paymentMethodChanged = TRUE;
            }
            
        }
        
        $result = array();
        
        if ($shippingMethodChanged){
            $result['update_section'] = array(
                'name' => 'shipping-method',
                'html' => $this->_getShippingMethodsHtml()
            );
            
        } else if ($paymentMethodChanged){
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        }
        
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    
    protected function _getAvailablePaymentMethods($quote){
        $store = $quote ? $quote->getStoreId() : null;
        
        $methods = Mage::helper('payment')->getStoreMethods($store, $quote);
        
        return $methods;
    }

    protected function _getCheckoutCartHtml(){  
        $cart_content = '';
        
        $cart = Mage::getSingleton('checkout/cart');
                
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init();
            $cart->save();
            
            $this->_initLayoutMessages('checkout/session');
            $this->_initLayoutMessages('catalog/session');
        
            $layout = Mage::getSingleton('core/layout');
            $cart = $layout->createBlock('checkout/cart');
            $cart->setTemplate('checkout/cart.phtml');
            $cart_content = $cart->toHTML();
            
        }
                
        
        return $cart_content;
    }
    
    protected function _getReviewHtml()
    {
        return $this->getLayout()->getBlock('root')->toHtml();
    }
    
}