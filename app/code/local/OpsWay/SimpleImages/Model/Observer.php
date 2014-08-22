<?php
  
class OpsWay_SimpleImages_Model_Observer
{
 
    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
             
        $product = $observer->getEvent()->getProduct();
     
        try {
            /**
             * Perform any actions you want here
             *
             */
            //$customFieldValue =  $this->_getRequest()->getPost('simpleimages_post_test');
            $customFieldValue = Mage::helper('core')->jsonDecode($observer->getRequest()->getPost('simpleimages_post_test'));
            echo "customFieldValue<pre>"; print_r($customFieldValue); echo "</pre>";
            die;

            /**
             * Uncomment the line below to save the product
             *
             */
            //$product->save();
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }
      
    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }
     
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

}