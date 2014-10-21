<?php
	class ECP_LinkShare_Model_Cron {
		
		private $lastDiff;
		private $lastFull;


		public function feedGenerate() {
			Mage::log("Start feed generate...", null, 'linkshare.log');
            $conf = Mage::getStoreConfig('link_share_settings/ftp');

            $rustart = getrusage();

            $diffGrouped = '';
            $countGrouped = 0;
            $diffConfigurable = '';
            $countConfigurable = 0;

            $productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('id')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('UPC')
                ->addAttributeToFilter('type_id', array('eq' => 'configurable'));
            $bullet="•";
            $merchant_id = Mage::getStoreConfig('link_share_settings/general/merchant_id');
            $store_name = 'swimsuitsdirect.com';
            $time_str = date('Y-m-d/G:i:s');
            $header = "HDR|$merchant_id|$store_name|$time_str";

            $storeCurrency = Mage::app()->getStore()->getCurrentCurrencyCode();
            $arr_files = array();

            $localPath = Mage::getStoreConfig('link_share_settings/ftp/localPath');
            $fullLocalPath = Mage::getBaseDir() . '/' . $localPath;

            Mage::getConfig()->createDirIfNotExists($fullLocalPath);
            $shortProdfile = $merchant_id . '_nmerchandis' . date('Ymd') . '_full.txt';
            $prodfile = $fullLocalPath . '/' . $shortProdfile;

            $shortAttrfile = $merchant_id . '_nattributes' . date('Ymd') . '_full.txt';
            $attrfile = $fullLocalPath . '/' . $shortAttrfile;

            $arr_files[] = $shortProdfile;
            $arr_files[] = $shortAttrfile;

            Mage::log($prodfile, null, 'linkshare.log');
            Mage::log($attrfile, null, 'linkshare.log');

            if (file_exists($prodfile)) {
                unlink($prodfile);
            }
            $logHandle = fopen($prodfile, 'w');
            fclose($logHandle);
            if (file_exists($attrfile)) {
                unlink($attrfile);
            }
            $logHandle = fopen($attrfile, 'w');
            fclose($logHandle);
            if (is_writeable($prodfile)) {
                $logHandle = fopen($prodfile, 'a');
                fwrite($logHandle, $header."\n");
                fclose($logHandle);
            }
            if (is_writeable($attrfile)) {
                $logHandle = fopen($attrfile, 'a');
                fwrite($logHandle, $header."\n");
                fclose($logHandle);
            }
            foreach ($productCollection as $productShort) {
                $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productShort->getId());
                if($product->getStatus() == '1'){
                    $is_active = "Y";
                    $is_deleted = "N";
                }
                else {
                    $is_deleted = "Y";
                    $is_active = "N";
                }
                $itemStr = $productShort->getId(); /* Product ID  */
                $itemStr .= '|'.$productShort->getName(); /* Product Name */
                $itemStr .= '|'.$productShort->getSku(); /* Sku Number */
                $itemStr .= '|'.'Swimsuits'; /* Primary Category */
                $itemStr .= '|'.''; /* Secondary Category(ies) null */
                if($product->getVisibility() == Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) {
                    $prodUrl = '';
                }
                else{
                    $prodUrl = $product->getUrlModel()->getUrl($product, array('_ignore_category'=>true));
                }

                $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                if(isset($parentIds[0])){
                    $groupedID = $parentIds[0];
                }

                // priority: first grouped, then configurable
                if(isset($groupedID)){
                    $parent = Mage::getModel('catalog/product')->load($groupedID);
                    $prodUrlParent = $parent->getUrlModel()->getUrl($parent, array('_ignore_category'=>true));
                    $hasParent = true;
                }
                else{
                    $prodUrlParent = $prodUrl;
                    $hasParent = false;
                }


                $itemStr .= '|'.$prodUrlParent; /* Product URL */
                $itemStr .= '|'.Mage::helper('catalog/image')
                        ->init($product, 'small_image')
                        ->backgroundColor(249,249,249)
                        ->resize(257,377); /* Product Image URL */
                $itemStr .= '|'; /* Buy URL, null */
                $itemStr .= '|'.str_replace(array("\n","\r",$bullet),' ',strip_tags($product->getShortDescription())); /* Short Product Description, stripped */
                $itemStr .= '|'; /* Long Product Description, getDescription, stripped, null */
                $itemStr .= '|'; /* Discount, you save, null */
                $itemStr .= '|'; /* Discount Type, Values: amount or percentage, null */
                $itemStr .= '|'.$product->getFinalPrice(); /* Sale Price, price with discount, null */
                $itemStr .= '|'.$product->getPrice(); /* Retail Price */
                $itemStr .= '|'; /* Begin Date, null */
                $itemStr .= '|'; /* End Date, null */
                $itemStr .= '|'.$product->getAttributeText('manufacturer'); /* Brand, null */
                $itemStr .= '|'; /* Shipping, null */
                $itemStr .= '|'.$is_deleted; /* Is Deleted Flag, N|Y */
                $itemStr .= '|'.$product->getMetaKeyword(); /* Keyword(s), null */
                $itemStr .= '|'.'Y'; /* Is All Flag, Y|N */
                $itemStr .= '|'.$product->getSKU(); /* Manufacturer Part #, null */
                $itemStr .= '|'.$product->getAttributeText('manufacturer'); /* Manufacturer Name, null */
                $itemStr .= '|'.''; /* Shipping Information, null */
                $itemStr .= '|'.$product->getQty(); /* Availability, null */
                $itemStr .= '|'.$productShort->getUPC(); /* Universal Product Code, UPC,  null */
                $itemStr .= '|'.'60'; /* Class ID, for attribute file */
                $itemStr .= '|'.$is_active; /* Is Product Link Flag, Y|N, Y for active */
                $itemStr .= '|'.$is_active; /* Is Storefront Flag, Y|N, Y for active */
                $itemStr .= '|'.$is_active; /* Is Merchandiser Flag, Y|N, Y for active */
                $itemStr .= '|'.$storeCurrency; /* Currency, USD */
                $itemStr .= '|'.''; /* M1, null */
                if (is_writeable($prodfile)) {
                    $logHandle = fopen($prodfile, 'a');
                    fwrite($logHandle, $itemStr."\n");
                    fclose($logHandle);
                }


                /* ATTRIBUTES */
                $itemStr = $productShort->getId(); /* Product ID */
                $itemStr .= '|'.'60'; /* Class ID, 60 = clothing and accessories */
                $itemStr .= '|'.''; /* Miscellaneous */
                $itemStr .= '|'.$product->getAttributeText('type_id'); /* Product Type */

                $attributeOptions = array();
                $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

                foreach ($productAttributeOptions as $productAttribute) {
                    if ($productAttribute['attribute_code'] == 'color' || $productAttribute['attribute_code'] == 'pattern'){
                        foreach ($productAttribute['values'] as $attribute) {
                            $attributeOptions['color'][$attribute['value_index']] = $attribute['store_label'];
                        }
                    }
                    if ($productAttribute['attribute_code'] == 'size' || $productAttribute['attribute_code'] == 'top_size' || $productAttribute['attribute_code'] == 'bottom_size'){
                        foreach ($productAttribute['values'] as $attribute) {
                            $attributeOptions['size'][$attribute['value_index']] = $attribute['store_label'];
                        }
                    }
                }

                unset($productAttributeOptions);

                if(isset($attributeOptions['color'])){
                    $colorA = implode(',',$attributeOptions['color']);
                }
                else{
                    $colorA = '';
                }
                if(isset($attributeOptions['size'])){
                    $sizeA = implode(',',$attributeOptions['size']);
                }
                else{
                    $sizeA = '';
                }
                unset($attributeOptions);

                $itemStr .= '|'.$sizeA; /* Size */


                /*$size = $product->getAttributeText('size');
                if($size == ''){
                    $size = $product->getAttributeText('top_size');
                }
                if($size == ''){
                    $size = $product->getAttributeText('bottom_size');
                }
                $itemStr .= '|'.$size;*/


                $itemStr .= '|'.''; /* Material */
                $itemStr .= '|'.$colorA; /* Color */

                /*$color = $product->getAttributeText('color');
                if($color == ''){
                    $color = $product->getAttributeText('pattern');
                }
                $itemStr .= '|'.$color;*/

                $itemStr .= '|'.'Women'; /* Gender */
                if($hasParent){
                    $style = $parent->getAttributeText('style');
                    if($style == ''){
                        $style = $parent->getAttributeText('top_style');
                    }
                    if($style == ''){
                        $style = $parent->getAttributeText('bottom_style');
                    }
                }
                else{
                    $style = $product->getAttributeText('style');
                    if($style == ''){
                        $style = $product->getAttributeText('top_style');
                    }
                    if($style == ''){
                        $style = $product->getAttributeText('bottom_style');
                    }
                }

                $itemStr .= '|'.$style; /* Style */
                $itemStr .= '|'.''; /* Age */
                if (is_writeable($attrfile)) {
                    $logHandle = fopen($attrfile, 'a');
                    fwrite($logHandle, $itemStr."\n");
                    fclose($logHandle);
                }
                continue;

            }

            $record_count = $productCollection->getSize();
            $trailer = "TRL|$record_count";
            if (is_writeable($prodfile)) {
                $logHandle = fopen($prodfile, 'a');
                fwrite($logHandle, $trailer."\n");
                fclose($logHandle);
            }
            if (is_writeable($attrfile)) {
                $logHandle = fopen($attrfile, 'a');
                fwrite($logHandle, $trailer."\n");
                fclose($logHandle);
            }

            Mage::helper('ecp_linkshare')->ftpUpload($arr_files);

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = ($endtime - $starttime);
            Mage::log("feed created in ".$totaltime." seconds", null, 'linkshare.log');
		}
    }
?>
