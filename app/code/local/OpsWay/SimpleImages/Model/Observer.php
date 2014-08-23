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
        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);  
     
        try {

            $simpleimagesColor = $this->_getRequest()->getPost('simpleimages_color');
            $simpleimagesGallery = $this->_getRequest()->getPost('simpleimages_gallery');

            if (is_array($simpleimagesGallery) && is_array($simpleimagesColor)){
                 
                foreach($childProducts as $_simple){
                    $_simple->setColor($simpleimagesColor[$_simple->getId()]);
                    $_simple->setMediaGallery (array('images'=>array (), 'values'=>array ())); //media gallery initialization
                    foreach ($simpleimagesGallery[$_simple->getId()] as $key => $value) {
                        $_simple->addImageToMediaGallery('media/catalog/product' . $value, array('image','thumbnail','small_image'), false, false); //assigning image, thumb and small image to media gallery
                    }
                    $_simple->save();
                }
            }

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