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
        // $simpleImagesColor = $this->_getRequest()->getPost('simpleimages_color');

        if (isset($_FILES['simpleimages_gallery']) && isset($_FILES['simpleimages_gallery']['error']))
        {
            $simpleImagesGallery = $_FILES['simpleimages_gallery'];
            // foreach ($_FILES['simpleimages_gallery']['error'] as $optionId => $errorCode)
            // {
            //     if (UPLOAD_ERR_OK == $errorCode)
            //     {
            //         //deleting file firstly
            //         @unlink($uploadDir . $optionId . '.jpg');
            //         //uploading new file
            //         move_uploaded_file($_FILES['amconf_icon']['tmp_name'][$optionId], $uploadDir . $optionId . '.jpg');
            //         Mage::getSingleton('core/session')->addSuccess('Color icon ' . $optionId . '.jpg added successfully.');
            //     }
            // }
        }
        //$simpleImagesGallery = $this->_getRequest()->getPost('simpleimages_gallery');
        $simpleImagesType = $this->_getRequest()->getPost('simpleimages_type');

        if(is_array($simpleImagesGallery)) {
         
            try {
                $product = $observer->getEvent()->getProduct();
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);

                $uploadDir = Mage::getBaseDir('media') . DIRECTORY_SEPARATOR . 'amconf' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;

                // echo "simpleImagesGallery<pre>"; print_r($simpleImagesGallery); echo "</pre>";

                // die('saveProductTabData');
                     
                foreach($childProducts as $_simple){
                    // if(isset($simpleImagesGallery[$_simple->getId()]) || isset($simpleImagesColor[$_simple->getId()])) {
                    //     if (isset($simpleImagesColor[$_simple->getId()])){
                    //         $_simple->setColor($simpleImagesColor[$_simple->getId()]);
                    //     }

                    //     if(isset($simpleImagesGallery[$_simple->getId()])) {
                    //         $_simple->setMediaGallery (array('images'=>array (), 'values'=>array ())); //media gallery initialization
                            
                    //         foreach ($simpleImagesGallery[$_simple->getId()] as $key => $value) {
                    //             $currImageTypes = array();
                    //             //check if current image has images type associated with it
                    //             if(in_array($key, $simpleImagesType[$_simple->getId()])) {
                    //                 foreach ($simpleImagesType[$_simple->getId()] as $imgType => $imgId) {
                    //                     if($imgId == $key) {
                    //                         array_push($currImageTypes, $imgType);
                    //                     }
                    //                 }
                    //             }

                    //             $_simple->addImageToMediaGallery('media/catalog/product' . $value, $currImageTypes, false, false); //assigning image types to media gallery
                    //         }
                    //     }

                    //     /**
                    //      * Uploading files
                    //      */
                    //     if (isset($_FILES['amconf_icon']) && isset($_FILES['amconf_icon']['error']))
                    //     {

                    //         foreach ($_FILES['amconf_icon']['error'] as $optionId => $errorCode)
                    //         {
                    //             if (UPLOAD_ERR_OK == $errorCode)
                    //             {
                    //                 //deleting file firstly
                    //                 @unlink($uploadDir . $optionId . '.jpg');
                    //                 //uploading new file
                    //                 move_uploaded_file($_FILES['amconf_icon']['tmp_name'][$optionId], $uploadDir . $optionId . '.jpg');
                    //                 Mage::getSingleton('core/session')->addSuccess('Color icon ' . $optionId . '.jpg added successfully.');
                    //             }
                    //         }
                    //     }
                        
                    //     $_simple->save();
                    // }
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