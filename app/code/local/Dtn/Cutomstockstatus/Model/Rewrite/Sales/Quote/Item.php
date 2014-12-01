<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
* @package Amasty_Stockstatus
*/
class Dtn_Cutomstockstatus_Model_Rewrite_Sales_Quote_Item extends Mage_Sales_Model_Quote_Item
{
    public function getMessage($string = true)
    {
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$this->getSku());
        if(!$product){
            $product = Mage::getModel('catalog/product')->load($this->getProduct()->getId());
        }
        $status = Mage::helper('cutomstockstatus')->getCustomStockStatus(Mage::getModel('catalog/product')->load($product->getId()));
        if ($status){
            $this->addMessage(strip_tags($status));
        }
        return parent::getMessage($string);
    }
}