<?php

class Jain_Ordertracking_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getTrackingPopupUrlBySalesModel($model)
    {
        if ($model instanceof Mage_Sales_Model_Order) {
            return $this->_getTrackingUrl('order_id', $model);
        } elseif ($model instanceof Mage_Sales_Model_Order_Shipment) {
            return $this->_getTrackingUrl('ship_id', $model);
        } elseif ($model instanceof Mage_Sales_Model_Order_Shipment_Track) {
            return $this->_getTrackingUrl('track_id', $model, 'getEntityId');
        }
        return '';
    }
	protected function _getTrackingUrl($key, $model, $method = 'getId')
    {
         if (empty($model)) {
             $param = array($key => ''); // @deprecated after 1.4.0.0-alpha3
         } else if (!is_object($model)) {
             $param = array($key => $model); // @deprecated after 1.4.0.0-alpha3
         } else {
             $param = array(
                 'hash' => Mage::helper('core')->urlEncode("{$key}:{$model->$method()}:{$model->getProtectCode()}")
             );
         }
         $storeId = is_object($model) ? $model->getStoreId() : null;
         $storeModel = Mage::app()->getStore($storeId);
         return $storeModel->getUrl('ordertracking/index/popup', $param);
    }
}