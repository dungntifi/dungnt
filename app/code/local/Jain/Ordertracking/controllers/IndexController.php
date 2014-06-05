<?php
include("Mage/Sales/controllers/OrderController.php");
class Jain_Ordertracking_IndexController extends Mage_Sales_OrderController
{

    public function preDispatch()
    { 
    }
	protected function _canViewOrder($order)
    {
        
		
        $availableStates = Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates();
        if ($order->getId() && in_array($order->getState(), $availableStates, $strict = true)
            ) {
			
            return true;
        }else{
		$this->_redirect('*/');
		exit;
        //return false;
		}
        
    }
    public function indexAction()
    {	
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	 public function popupAction()
    {
        $shippingInfoModel = Mage::getModel('shipping/info')->loadByHash($this->getRequest()->getParam('hash'));
        Mage::register('current_shipping_info', $shippingInfoModel);
        if (count($shippingInfoModel->getTrackingInfo()) == 0) {
            $this->norouteAction();
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }
    protected function _loadValidOrder($orderId = null)
    {
	 $flag=0;
	 if ($this->getRequest()->isPost()) {
           $order_id = $this->getRequest()->getPost('order_id');
		   $email=$this->getRequest()->getPost('visitoremail');
		   $flag=1;
			}
			
        if (null === $orderId) {
            $orderId = (int) $this->getRequest()->getParam('order_id');
        }
        if (!$orderId) {
            $this->_forward('noRoute');
			$this->_redirect('*/*');
			Mage::getSingleton('checkout/session')->addError("invalid data");
			Mage::getSingleton('customer/session')->addError('invalid data');
            return false;
        }
		$read= Mage::getSingleton('core/resource')->getConnection('admin_read');
		if($flag==1){
		
		$orderArr = $read->fetchAll("select s.entity_id from sales_flat_order as  s where  s.increment_id = '".addslashes($order_id)."' AND s.customer_email ='".addslashes($email)."'");
		
		if(!empty($orderArr)){
		$orderId=$orderArr[0]["entity_id"];
		}else{
			Mage::getSingleton('checkout/session')->addError("invalid data");
			Mage::getSingleton('customer/session')->addError('invalid data');
			
			 $this->_redirect('*/*');
			return false;
		}
		}else{

		$orderArr = $read->fetchAll("select s.entity_id from sales_flat_order as  s where  s.increment_id = '".$orderId."'");
				
				if(!empty($orderArr)){
				$orderId=$orderArr[0]["entity_id"];
				}else{
					Mage::getSingleton('checkout/session')->addError("invalid data");
					Mage::getSingleton('customer/session')->addError('invalid data');
					
					 $this->_redirect('*/*');
					return false;
				}
		}
        $order = Mage::getModel('sales/order')->load($orderId);

        if ($this->_canViewOrder($order)) {
		$data=$order->getData();
		
            Mage::register('current_order', $order);
            return true;
			
        }
        else {
		Mage::getSingleton('checkout/session')->addError("invalid data");
		Mage::getSingleton('customer/session')->addError(Mage::helper('ordertracking')->__('invalid data'));
            $this->_redirect('*/*');
        }
        return false;
    }
}