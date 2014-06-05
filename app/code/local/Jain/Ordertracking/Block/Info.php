<?php
class Jain_Ordertracking_Block_Info extends Mage_Sales_Block_Order_Info
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
        protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ordertracking/info.phtml');
    }
	
	   public function getReorderUrl($order)
    {
        return $this->getUrl('ordertracking/reorder', array('order_id' => $order->getId()));
    }

      public function getPrintUrl($order)
    {
        return $this->getUrl('ordertracking/index/print', array('order_id' => $this->getOrder()->getIncrementId()));
    }
	
	 public function addLink($name, $path, $label)
    {
        $this->_links[$name] = new Varien_Object(array(
            'name' => $name,
            'label' => $label,
            'url' => empty($path) ? '' : Mage::getUrl($path, array('order_id' => $this->getOrder()->getIncrementId()))
        ));
        return $this;
    }
	   public function isCustomerNotificationNotApplicable(Mage_Sales_Model_Order_Status_History $history)
    {
        return $history->isCustomerNotificationNotApplicable();
    }
     
}