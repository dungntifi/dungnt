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
        $simpleImagesColor = $this->_getRequest()->getPost('simpleimages_color');
        $simpleImagesGallery = $this->_getRequest()->getPost('simpleimages_gallery');
        $simpleImagesType = $this->_getRequest()->getPost('simpleimages_type');

        if(is_array($simpleImagesColor) || is_array($simpleImagesGallery)) {
         
            try {
                $product = $observer->getEvent()->getProduct();
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);
                     
                foreach($childProducts as $_simple){
                    if(isset($simpleImagesGallery[$_simple->getId()]) || isset($simpleImagesColor[$_simple->getId()])) {
                        if (isset($simpleImagesColor[$_simple->getId()])){
                            $_simple->setColor($simpleImagesColor[$_simple->getId()]);
                        }

                        if(isset($simpleImagesGallery[$_simple->getId()])) {
                            $_simple->setMediaGallery (array('images'=>array (), 'values'=>array ())); //media gallery initialization
                            
                            foreach ($simpleImagesGallery[$_simple->getId()] as $key => $value) {
                                $currImageTypes = array();
                                //check if current image has images type associated with it
                                if(in_array($key, $simpleImagesType[$_simple->getId()])) {
                                    foreach ($simpleImagesType[$_simple->getId()] as $imgType => $imgId) {
                                        if($imgId == $key) {
                                            array_push($currImageTypes, $imgType);
                                        }
                                    }
                                }

                                $_simple->addImageToMediaGallery('media/catalog/product' . $value, $currImageTypes, false, false); //assigning image types to media gallery
                            }
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