<?php

class Magik_Pricedropalert_Model_Pricedropalert extends Mage_Core_Model_Abstract
{
    public function _construct(){
       parent::_construct();	
       $this->_init("pricedropalert/pricedropalert");

    }

    public function getList()
	{    	
		$pricedropCollection=Mage::getModel('pricedropalert/pricedropalert')
							->getCollection()
							->addFieldToSelect('*');			
	    return $pricedropCollection->getData();
	}

	public function getList1()
	{    		
		$pricedropCollection=Mage::getModel('pricedropalert/pricedropalert')
							->getCollection()
							->addFieldToSelect('*')
							->addFieldToFilter('active_status', 0);		
	    return $pricedropCollection->getData();	
	}
	public function chkifExists($id,$email)
	{		
		$pricedropCollection=Mage::getModel('pricedropalert/pricedropalert')
							->getCollection()
							->addFieldToSelect('id')
							->addFieldToFilter('productid', $id)
							->addFieldToFilter('email', $email);
	    return $pricedropCollection->getData();	   
	}
	public function getProductId()
	{
		$products = Mage::getModel('catalog/product')->getCollection();
		$products->addAttributeToFilter('status', 1); // enabled
		$products->addAttributeToFilter('visibility', 4); // catalog, search
		$products->addAttributeToSelect('*');
		$prod = $products->getAllIds();
		return $prod;
	}
	public function getPrice($productId)
	{
		$product = Mage::getModel('catalog/product');
		$product->load($productId);
		$price = ($product->getSpecialPrice()) ? $product->getSpecialPrice() : $product->getPrice();
		return $price;
	}
	public function updateDB($id,$newp)
	{		
		$pricedropModel=Mage::getModel('pricedropalert/pricedropalert')->load($id);
		$pricedropModel->setProductPrice($newp);
		$pricedropModel->setProductStatus(1);
		$pricedropModel->save();
	}
	
	public function getCustomDetails($emailId)
	{
		$pricedropCollection=Mage::getModel('pricedropalert/pricedropalert')
		->getCollection()
		->addFieldToSelect('*')		
		->addFieldToFilter('email', $emailId);
	    return $pricedropCollection->getData();	

	}

}
	 