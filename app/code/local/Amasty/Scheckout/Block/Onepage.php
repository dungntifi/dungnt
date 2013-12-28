<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Scheckout
*/
class Amasty_Scheckout_Block_Onepage extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getAreas(){
        $storeId = Mage::app()->getStore()->getStoreId();
        $areas = Mage::getModel("amscheckout/area")->getAreas($storeId, TRUE);
        
//        $areas[] = array(
//            'area_id' => 0,
//            'area_key' => 'login',
//            'default_area_label' => 'login',
//            'area_label' => 'login'
//        );
//        
        return $areas;
    }
    
    public function getUrl($route = '', $params = array())
    {
        
        $url = parent::getUrl($route, $params);
        
        if (isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'] && '' != $_SERVER['HTTPS'])
        {
            $url = str_replace('http:', 'https:', $url);
        } else {
            $url = str_replace('https:', 'http:', $url);
        }
        
        return $url;
    }
    
    public function getFields(){
        $storeId = Mage::app()->getStore()->getStoreId();
        return Mage::getModel("amscheckout/field")->getFields($storeId);
    }
    
    public function getDefaultCheckoutUrl(){
        return $this->getUrl('checkout/onepage/index', array(
            'default' => 1
        ));
    }
    
    public function getAfterAjaxUrl(){
        return $this->getUrl('amscheckout/onepage/ajax');
    }
    
    public function getBillngSaveUrl(){
        return $this->getUrl('checkout/onepage/saveBilling');
    }
    
    public function getShippingSaveUrl(){
        return $this->getUrl('checkout/onepage/saveShipping');
    }
    
    public function getShippingMethodSaveUrl(){
        return $this->getUrl('checkout/onepage/saveShippingMethod');
    }
    
    public function getPaymentSaveUrl(){
        return $this->getUrl('checkout/onepage/savePayment');
    }
    
    public function getPaymentMethodSaveUrl(){
        return $this->getUrl('amscheckout/onepage/savePayment');
    }
    
    public function getMethodSaveUrl(){
        return $this->getUrl('checkout/onepage/saveMethod');
    }
    
    public function getShoppingCartSaveUrl(){
        return $this->getUrl('amscheckout/cart/updatePost');
    }
    
    public function getShoppingCartDeleteUrl(){
        return $this->getUrl('amscheckout/cart/delete');
    }
    
    public function getSaveDefaultsUrl(){
        return $this->getUrl('amscheckout/onepage/saveDefaults');
    }
    
    public function getLoginPostAction()
    {
        return $this->getUrl('customer/account/loginPost');
    }
    
    public function getCouponPostUrl()
    {
        return $this->getUrl('amscheckout/cart/couponPost');
    }
    
//    couponPostUrl
    
    public function getLayoutType(){
        $storeId = Mage::app()->getStore()->getStoreId();
        return Mage::getModel("amscheckout/config")->getLayoutType($storeId);
    }
    
    public function getBillingRefreshableFields(){
        $ret = array();
        
        if (Mage::helper('core')->isModuleEnabled('Amasty_Table')){
            $ret['id']['region_id'] = 'billing:region_id';
            $ret['id']['region'] = 'billing:region';
            $ret['id']['city'] = 'billing:city';
            $ret['id']['postcode'] = 'billing:postcode';
        }
        
//        if (Mage::helper('core')->isModuleEnabled('Amasty_Shiprestriction') ||
//                Mage::helper('core')->isModuleEnabled('Amasty_Payrestriction')
//                ){
            $ret['name']['street'] = 'billing[street][]';
            
            $ret['id']['region_id'] = 'billing:region_id';
            $ret['id']['region'] = 'billing:region';
            $ret['id']['city'] = 'billing:city';
            $ret['id']['postcode'] = 'billing:postcode';
//        }
        
        return $ret;
    }
    
    public function getShippingRefreshableFields(){
        $ret = array();
        
        if (Mage::helper('core')->isModuleEnabled('Amasty_Table')){
            $ret['id']['region_id'] = 'shipping:region_id';
            $ret['id']['region'] = 'shipping:region';
            $ret['id']['city'] = 'shipping:city';
            $ret['id']['postcode'] = 'shipping:postcode';
        }
        
//        if (Mage::helper('core')->isModuleEnabled('Amasty_Shiprestriction') ||
//                Mage::helper('core')->isModuleEnabled('Amasty_Payrestriction')){
            $ret['name']['street'] = 'shipping[street][]';
            
            $ret['id']['region_id'] = 'shipping:region_id';
            $ret['id']['region'] = 'shipping:region';
            $ret['id']['city'] = 'shipping:city';
            $ret['id']['postcode'] = 'shipping:postcode';
//        }
        
        return $ret;
    }
    
    function isReloadShippingAfterPaymentCheck(){
        return Mage::helper('core')->isModuleEnabled('Amasty_Shiprestriction');
    }
    
    function isGuestCheckoutEnabled(){
        return Mage::getStoreConfig('checkout/options/guest_checkout') == 1;
    }
    
    function isCustomerMustBeLogged(){
        return Mage::getStoreConfig('checkout/options/customer_must_be_logged') == 1;
    }
    
    function getStoreConfig(){
        return array(
            'shopping_cart' => array(
                'cart_to_checkout' => Mage::getStoreConfig('amscheckout/shopping_cart/cart_to_checkout'),
                'qty_updatable' => Mage::getStoreConfig('amscheckout/shopping_cart/qty_updatable'),
                'editable' => Mage::getStoreConfig('amscheckout/shopping_cart/editable'),
                'delitable' => Mage::getStoreConfig('amscheckout/shopping_cart/delitable'),
            )
        );
    }
    
   
}
