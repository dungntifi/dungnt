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
        
        if(isset($_FILES['simpleimages_gallery'])) {
            $simpleImagesGallery = $_FILES['simpleimages_gallery'];
        }
        $simpleImagesData = $this->_getRequest()->getPost('simpleimages_data'); //contains label, sort order, remove options
        $simpleImagesType = $this->_getRequest()->getPost('simpleimages_type');
        $colorPosition = $this->_getRequest()->getPost('color_position');

        // echo "simpleImagesGallery<pre>"; print_r($simpleImagesGallery); echo "</pre>";
        // echo "simpleImagesData<pre>"; print_r($simpleImagesData); echo "</pre>";
        // echo "simpleImagesType<pre>"; print_r($simpleImagesType); echo "</pre>";
        // echo "colorPosition<pre>"; print_r($colorPosition); echo "</pre>";
     
        //proceed only if any data exists
        if(isset($simpleImagesGallery) || is_array($simpleImagesData) || is_array($simpleImagesType)) {

            try {
                $product = $observer->getEvent()->getProduct();
                $ids     = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($product->getId());   
                $childProducts = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToFilter('entity_id', $ids)
                    ->addAttributeToSelect('color')
                    ->addAttributeToSelect('color_position');
                    
                foreach($childProducts as $_simple){

                    //Mage::log('===START ' . $_simple->getId() . '===');

                    //preset data
                    $colorId = $_simple->getColor();
                    if(isset($simpleImagesGallery['name'][$colorId][0]) || isset($simpleImagesType[$colorId][$_simple->getId()])) {
                        $_simple->setMediaGallery(array('images'=>array(), 'values'=>array())); //media gallery initialization
                    }

                    //STEP 1: assign uploaded files
                    if(isset($simpleImagesGallery['name'][$colorId][0]) && !empty($simpleImagesGallery['name'][$colorId][0])) {                   
                        foreach ($simpleImagesGallery['name'][$colorId] as $key => $name) {
                            $_simple->addImageToMediaGallery('media/simpleimages/' . $name, null, false, false); //assigning image types to media gallery
                        }
                    }

                    //STEP 2: set gallery image data
                    if(is_array($simpleImagesData) || is_array($simpleImagesType)) {

                        $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
                        $items = $mediaApi->items($_simple->getId());

                        foreach($items as $key => $item) {                        
                            //update label  
                            if(isset($simpleImagesData['label'][$colorId][$key])) {
                                $mediaApi->update($_simple->getId(), $item['file'], array('label' => $simpleImagesData['label'][$colorId][$key]));
                            }

                            //update position
                            if(isset($simpleImagesData['position'][$colorId][$key])) {
                                $mediaApi->update($_simple->getId(), $item['file'], array('position' => $simpleImagesData['position'][$colorId][$key]));
                            }

                            //remove images
                            if(isset($simpleImagesData['remove'][$colorId])) {
                                $itemName = $itemAlias = $fileName = $fileAlias = $itemNameLastCharacter = $itemNameOffset = $fileNameLastCharacter = $fileNameOffset = '';
                                foreach ($simpleImagesData['remove'][$colorId] as $file) {
                                    //Mage::log('---------------------------------');
                                    $itemName = explode('.', $item['file']);
                                    //Mage::log('itemName ' . print_r($itemName, true));
                                    $fileName = explode('.', $file);
                                    //Mage::log('fileName ' . print_r($fileName, true));

                                    //in case filename has _, like /g/o/gold_2_8_1_1.jpg and /g/o/gold_2_8_1_2.jpg
                                    //reducing /g/o/gold_2_8_1_1.jpg and /g/o/gold_2_8_1_2.jpg to /g/o/gold_2_8_1
                                    if(strpos($itemName[0], '_') && strpos($fileName[0], '_')) {
                                        //Mage::log('--- itemName && fileName pathes has _');
                                        $itemNameLastCharacter = explode('_', $itemName[0]);
                                        //Mage::log('itemNameLastCharacter ' . print_r($itemNameLastCharacter, true));
                                        $itemNameOffset = -1 * (int)strlen(end($itemNameLastCharacter));
                                        //Mage::log('itemNameOffset ' . $itemNameOffset);
                                        $fileNameLastCharacter = explode('_', $fileName[0]);
                                        //Mage::log('fileNameLastCharacter ' . print_r($fileNameLastCharacter, true));
                                        $fileNameOffset = -1 * (int)strlen(end($fileNameLastCharacter));
                                        //Mage::log('fileNameOffset ' . $fileNameOffset);
                                        $itemAlias = substr($itemName[0], 0, $itemNameOffset);
                                        //Mage::log('itemAlias ' . $itemAlias);
                                        $fileAlias = substr($fileName[0], 0, $fileNameOffset);
                                        //Mage::log('fileAlias ' . $fileAlias);

                                    } elseif(strpos($itemName[0], '_')) {//check if one path like /7/1/712_1.jpg and other like /7/1/712.jpg - delete if true
                                        //Mage::log('--- itemName path has _');
                                        $itemNameLastCharacter = explode('_', $itemName[0]);
                                        //Mage::log('itemNameLastCharacter ' . print_r($itemNameLastCharacter, true));
                                        $itemNameOffset = -1 * (int)strlen(end($itemNameLastCharacter));
                                        //Mage::log('itemNameOffset ' . $itemNameOffset);
                                        $itemAlias = substr($itemName[0], 0, $itemNameOffset);
                                        //Mage::log('itemAlias ' . $itemAlias);
                                        $fileAlias = $fileName[0] . '_'; //adding trailing _ like itemAlias has 
                                        //Mage::log('fileAlias ' . $fileAlias);

                                    } elseif(strpos($fileName[0], '_')) {//check if one path like /7/1/712_1.jpg and other like /7/1/712.jpg - delete if true
                                        //Mage::log('--- fileName path has _');
                                        $fileNameLastCharacter = explode('_', $fileName[0]);
                                        //Mage::log('fileNameLastCharacter ' . print_r($fileNameLastCharacter, true));
                                        $fileNameOffset = -1 * (int)strlen(end($fileNameLastCharacter));
                                        //Mage::log('fileNameOffset ' . $fileNameOffset);
                                        $fileAlias = substr($fileName[0], 0, $fileNameOffset);
                                        //Mage::log('fileAlias ' . $fileAlias);
                                        $itemAlias = $itemName[0] . '_'; //adding trailing _ like fileAlias has 
                                        //Mage::log('itemAlias ' . $itemAlias);

                                    } else {//we assume that filenames looks like /7/1/712.jpg - whithout _ delimiter
                                        //Mage::log('--- file pathes has NO _');
                                        $itemAlias = $itemName[0];
                                        //Mage::log('itemName ' . $itemName[0]);
                                        $fileAlias = $fileName[0];
                                        //Mage::log('fileName ' . $fileName[0]);
                                    }
                                    
                                    if($itemAlias == $fileAlias) {
                                        //Mage::log('itemAlias == fileAlias');
                                        //Mage::log('removing ' . $item['file']);
                                        $mediaApi->remove($_simple->getId(), $item['file']);
                                        $itemName = $itemAlias = $fileName = $fileAlias = $itemNameLastCharacter = $itemNameOffset = $fileNameLastCharacter = $fileNameOffset = '';
                                    }
                                    //Mage::log('---------------------------------');
                                }                           
                            }
                        }

                        //STEP 3: update image types
                        if(isset($simpleImagesType[$colorId])) {

                            $groupedByPath = ''; $fileName = $alias = $lastCharacter = $offset = '';
                            /**
                             * NOTE: alias is reducing file name by 2 symbols at the end to allow update all image types
                             * in case they have equal name in different simple products. 
                             * For example: /g/o/gold_2_8_1_1.jpg and /g/o/gold_2_8_1_2.jpg will be transformed to /g/o/gold_2_8_1
                             * and both entries will be updated
                             */

                            //transform data to array('%image_path%' => array('%image_type'))
                            foreach ($simpleImagesType[$colorId] as $type => $path) {
                                $fileName = explode('.', $path);
                                $lastCharacter = explode('_', $fileName[0]);
                                $offset = -1 * (int)strlen(end($lastCharacter));
                                $alias = substr($fileName[0], 0, $offset);
                                $groupedByPath[$alias][] = $type;
                            }

                            // Mage::log('---STEP3---');
                            // Mage::log('items' . print_r($items, true));
                            // Mage::log('groupedByPath' . print_r($groupedByPath, true));

                            $fileName = $alias = $lastCharacter = $offset = '';
                            foreach($items as $key => $item) {
                                $fileName = explode('.', $item['file']);
                                $lastCharacter = explode('_', $fileName[0]);
                                $offset = -1 * (int)strlen(end($lastCharacter));
                                $alias = substr($fileName[0], 0, $offset);
                                //update gallery image types
                                if(isset($groupedByPath[$alias])) { 
                                    $mediaApi->update($_simple->getId(), $item['file'], array('types' => $groupedByPath[$alias]));
                                }
                            }
                        }

                        //STEP 4: set color position
                        if(isset($colorPosition[$colorId]) && $_simple->getData('color_position')) {
                            $_simple->setData('color_position', $colorPosition[$colorId]); 
                        }
                    }

                    //Mage::log('===END ' . $_simple->getId() . '===');
                    $_simple->save();
                
                }//endforeach
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