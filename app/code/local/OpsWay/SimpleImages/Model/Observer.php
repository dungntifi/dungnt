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
        
        $this->_uploadIconImages($_FILES);
        $this->_uploadProductImages($_FILES);
        
        $simpleImagesGallery = $_FILES['simpleimages_gallery'];
        $simpleImagesType = $this->_getRequest()->getPost('simpleimages_type');

        if(is_array($simpleImagesGallery)) {

            // echo "simpleImagesGallery<pre>"; print_r($simpleImagesGallery); echo "</pre>";
            // echo "Files<pre>"; print_r($_FILES); echo "</pre>"; die;
         
            try {
                $product = $observer->getEvent()->getProduct();
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null,$product);
                     
                foreach($childProducts as $_simple){
                    $colorId = $_simple->getColor();

                    //assign uploaded files
                    if(isset($simpleImagesGallery['name'][$colorId][0]) && !empty($simpleImagesGallery['name'][$colorId][0])) {
                        $_simple->setMediaGallery (array('images'=>array (), 'values'=>array ())); //media gallery initialization
                        
                        foreach ($simpleImagesGallery['name'][$colorId] as $key => $name) {
                            Mage::log('color ' . $colorId . ' media/simpleimages/' . $name);
                            // $currImageTypes = array();
                            // //check if current image has images type associated with it
                            // if(in_array($key, $simpleImagesType[$_simple->getId()])) {
                            //     foreach ($simpleImagesType[$_simple->getId()] as $imgType => $imgId) {
                            //         if($imgId == $key) {
                            //             array_push($currImageTypes, $imgType);
                            //         }
                            //     }
                            // }

                            $_simple->addImageToMediaGallery('media/simpleimages/' . $name, null, false, false); //assigning image types to media gallery
                        }
                    }
                    
                    $_simple->save();
                }
                
                $product->save();
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    private function _uploadIconImages($_FILES) {

        if (isset($_FILES['amconf_icon']) && isset($_FILES['amconf_icon']['error']))
        {
            $uploadDir = Mage::getBaseDir('media') . DIRECTORY_SEPARATOR . 'amconf' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            foreach ($_FILES['amconf_icon']['error'] as $colorId => $error)
            {
                foreach ($error as $key => $value) {
                    if (UPLOAD_ERR_OK == $value)
                    {
                        //deleting file firstly
                        @unlink($uploadDir . $colorId . '.jpg');
                        //uploading new file
                        move_uploaded_file($_FILES['amconf_icon']['tmp_name'][$colorId][$key], $uploadDir . $colorId . '.jpg');
                    }
                }
            }
        }
    }

    private function _uploadProductImages($_FILES) {
        
        if (isset($_FILES['simpleimages_gallery']) && isset($_FILES['simpleimages_gallery']['error']))
        {
            foreach ($_FILES['simpleimages_gallery']['error'] as $colorId => $error)
            {
                foreach ($error as $key => $value) {

                    if (UPLOAD_ERR_OK == $value)
                    {
                        try {
                            $fileName       = $_FILES['simpleimages_gallery']['name'][$colorId][$key];
                            $fileExt        = strtolower(substr(strrchr($fileName, "."), 1));
                            $fileNamewoe    = rtrim($fileName, $fileExt);
                            $fileName       = str_replace(' ', '', $fileNamewoe) . $fileExt;

                            $uploader = new Varien_File_Uploader(
                                array(
                                    'name' => $_FILES['simpleimages_gallery']['name'][$colorId][$key],
                                    'type' => $_FILES['simpleimages_gallery']['type'][$colorId][$key],
                                    'tmp_name' => $_FILES['simpleimages_gallery']['tmp_name'][$colorId][$key],
                                    'error' => $_FILES['simpleimages_gallery']['error'][$colorId][$key],
                                    'size' => $_FILES['simpleimages_gallery']['size'][$colorId][$key]
                                )
                            );
                            $uploader->setAllowedExtensions(array('png','gif', 'jpg', 'jpeg'));
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);

                            $path = Mage::getBaseDir('media') . DS . 'simpleimages';
                            if(!is_dir($path)){
                                mkdir($path, 0777, true);
                            }

                            $uploader->save($path . DS, $fileName);

                        } catch (Exception $e) {
                            Mage::log($e->getMessage());
                        }
                    }
                }
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