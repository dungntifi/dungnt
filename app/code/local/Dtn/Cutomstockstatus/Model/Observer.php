<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 11/27/14
 * Time: 4:37 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Cutomstockstatus_Model_Observer{
    public function setQtyOfProductAfterCheckout(Varien_Event_Observer $observer){
        $orderIds = $observer->getEvent()->getOrderIds();
        $order = Mage::getModel("sales/order")->load($orderIds);
        $ordered_items = $order->getAllItems();
        foreach($ordered_items as $item){
            if($item->getProductType()=='configurable') continue;
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            Mage::helper('cutomstockstatus')->checkEnableTypeQty($product, $item->getQtyOrdered());
        }
        return $this;
    }

    public function checkQuantityItemQuote(Varien_Event_Observer $observer){
        if($observer->getEvent()->getControllerAction()->getFullActionName() == "checkout_cart_add")
        {
            $request = Mage::app()->getRequest()->getParams();
            if($request['simple-product-id']){
                $productId = $request['simple-product-id'];
            }else{
                $productId = $request['product'];
            }
            $helper = Mage::helper('cutomstockstatus');
            $product = Mage::getModel('catalog/product')->load($productId);
            $qty = $helper->getTotalQtyOfItem($product);
            if($request['qty'] > $qty) {
                Mage::getSingleton('core/session')->addError('The maximum quantity allowed for purchase is '.$qty);
                if($request['simple-product-id']){
                    $productPrarent = Mage::getModel('catalog/product')->load($request['product']);
                    header("Location: " . $productPrarent->getProductUrl());die();
                }else{
                    header("Location: " . $product->getProductUrl());die();
                }
            }else{
                return $this;
            }
        }
    }

}