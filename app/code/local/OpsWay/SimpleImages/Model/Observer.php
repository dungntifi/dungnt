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
            $simpleimagesProduct = $this->_getRequest()->getPost('simpleimages_product');
            $simpleimagesColor = $this->_getRequest()->getPost('simpleimages_color');

            if (is_array($simpleimagesProduct) && is_array($simpleimagesColor)){

                // echo "simpleimagesProduct<pre>"; print_r($simpleimagesProduct); echo "</pre>";
                //echo "simpleimagesColor<pre>"; print_r($simpleimagesColor); echo "</pre>";
                // die;
                 
                foreach($simpleimagesProduct as $_image => $simpleId){
                    $_simple = Mage::getModel('catalog/product')->loadByAttribute('entity_id', $simpleId);
                    //echo "simple $simpleId <pre>"; print_r($_simple->getData()); echo "</pre>";
                    $_simple->setColor($simpleimagesColor[$_image]);
                    $_simple->save();
                }
            }

            /**
             * Uncomment the line below to save the product
             *
             */
            $product->save();
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