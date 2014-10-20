<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/14/14
 * Time: 3:36 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_All_Model_Observer{
    public function saveEventDateItem(Varien_Event_Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        $params = Mage::app()->getRequest()->getParam("event_date");
        foreach($order->getAllItems() as $item) {
            foreach($params as $key=>$value){
                if($item->getProductId()==$key){
                    $item->setEventDate($value);
                }
            }
        }
        return $this;
    }

    public function saveEventDateItemInInvoice(Varien_Event_Observer $observer) {
        $invoice = $observer->getEvent()->getInvoice();
        foreach($invoice->getAllItems() as $item) {
            $order = Mage::getModel('sales/order_item')->load($item->getOrderItemId());
            $item->setEventDate($order->getEventDate());
        }
        return $this;
    }

    public function saveEventDateItemInShipment(Varien_Event_Observer $observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        foreach($shipment->getAllItems() as $item) {
            $order = Mage::getModel('sales/order_item')->load($item->getOrderItemId());
            $item->setEventDate($order->getEventDate());
        }
        return $this;
    }
}