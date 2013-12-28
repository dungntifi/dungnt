<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Scheckout
*/
class Amasty_Scheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
    function getDefaultPeymentMethod($defaultPaymentMethod = NULL){
//        $paymentMethods = Mage::helper('payment')->getStoreMethods(Mage::app()->getStore()->getId());
        
        $ret = NULL;
        
        $paymentMethods = Mage::getSingleton('core/layout')->getBlock('checkout.payment.methods')->getMethods();
        $paymentMethods = array_values($paymentMethods);
        
        foreach($paymentMethods as $paymentMethod){
            if ($defaultPaymentMethod == $paymentMethod->getCode()){
                $ret = $defaultPaymentMethod;
                break;
            }
        }
        
        
        if ($ret === NULL && isset($paymentMethods[0]))
            $ret = $paymentMethods[0]->getCode();
        
        return $ret;
    }
    
    function getDefaultShippingMethod(){
        $ret = NULL;
        $address = Mage::getSingleton('core/layout')->getBlock('checkout.onepage.payment')->getQuote()->getShippingAddress();
        
        $address->collectShippingRates()->save();
        
        $_shippingRateGroups = $address->getGroupedAllShippingRates();
        
        foreach ($_shippingRateGroups as $code => $_rates){
            foreach ($_rates as $_rate){
                $ret = $_rate->getCode();
                    break;
                }
            }
        
        return $ret;
//
//
//        $ret = NULL;
//        
//        $shippingMethods = Mage::getSingleton('shipping/config')
//                ->getActiveCarriers(Mage::app()->getStore()->getId());
//
//        foreach($shippingMethods as $fieldKey => $_carrier){
//            if($_methods = $_carrier->getAllowedMethods())  {
//                foreach($_methods as $method => $title){
//                    $ret = $fieldKey.'_'.$method;
//                    break;
//                }
//            }
//        }
//        
//        return $ret;
    }
}
?>