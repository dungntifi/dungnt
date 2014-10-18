<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Conf
*/
class Amasty_Conf_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    protected $_optionProducts;
    
    protected function _afterToHtml($html)
    {
        $attributeIdsWithImages = Mage::registry('amconf_images_attrids');
        $html = parent::_afterToHtml($html);
        if ('product.info.options.configurable' == $this->getNameInLayout())
        {
            if (Mage::getStoreConfig('amconf/general/hide_dropdowns') )
            {
                if (!empty($attributeIdsWithImages))
                {
                    foreach ($attributeIdsWithImages as $attrIdToHide)
                    {
                        $html = preg_replace('@(id="attribute' . $attrIdToHide . ')(-)?([0-9]*)(")(\s+)(class=")(.*?)(super-attribute-select)(-)?([0-9]*)@', '$1$2$3$4$5$6$7$8$9$10 no-display', $html);
                    }
                }
            }            

            // both config setting and product attribute should be set to "Yes"
            $_useSimplePrice =  (Mage::helper('amconf')->getConfigUseSimplePrice() AND $this->getProduct()->getData('amconf_simple_price'))? true : false;
            
            $simpleProducts = $this->getProduct()->getTypeInstance(true)->getUsedProducts(null, $this->getProduct());
            if ($this->_optionProducts)
            {
                $noimgUrl = Mage::helper('amconf')->getNoimgImgUrl();
                $this->_optionProducts = array_values($this->_optionProducts);
                foreach ($simpleProducts as $simple)
                {
                    /* @var $simple Mage_Catalog_Model_Product */
                    $key = array();
                    for ($i = 0; $i < count($this->_optionProducts); $i++)
                    {
                        foreach ($this->_optionProducts[$i] as $optionId => $productIds)
                        {
                            if (in_array($simple->getId(), $productIds))
                            {
                                $key[] = $optionId;
                            }
                        }
                    }
                    if ($key)
                    {
                        $strKey = implode(',', $key);
                        // @todo check settings:
                        // array key here is a combination of choosen options
                        $_prod = Mage::getModel('catalog/product')->load($simple->getId());
                        $small_image = $thumbnail = '';
                        if($_prod->getSmallImage()) {
                            $small_image = Mage::getModel('catalog/product_media_config')->getMediaUrl($_prod->getSmallImage());
                        }
                        if($_prod->getThumbnail()) {
                            $thumbnail = Mage::getModel('catalog/product_media_config')->getMediaUrl($_prod->getThumbnail());
                        }   

                        $confDataSingle[$strKey] = array(
                            'short_description' => $this->helper('catalog/output')->productAttribute($simple, nl2br($simple->getShortDescription()), 'short_description'),
                            'description'       => $this->helper('catalog/output')->productAttribute($simple, $simple->getDescription(), 'description'),
                            'small_image'       => $small_image,
                            'thumbnail'         => $thumbnail,
                        );
                         
                        if (Mage::getStoreConfig('amconf/general/reload_name'))
                        {
                            $confDataSingle[$strKey]['name'] = $simple->getName();
                        }
                 
                        
                        if ($_useSimplePrice)
                        {
                            $tierPriceHtml = $this->getTierPriceHtml($simple);
                            $confDataSingle[$strKey]['price_html'] = str_replace('product-price-' . $simple->getId(), 'product-price-' . $this->getProduct()->getId(), $this->getPriceHtml($simple) . $tierPriceHtml);
                            $confDataSingle[$strKey]['price_clone_html'] = str_replace('product-price-' . $simple->getId(), 'product-price-' . $this->getProduct()->getId(), $this->getPriceHtml($simple, false, '_clone') . $tierPriceHtml);

                            // the price value is required for product list/grid
                            $confDataSingle[$strKey]['price'] = $simple->getFinalPrice();
                        }
                        
                        if ($simple->getImage() && Mage::getStoreConfig('amconf/general/reload_images'))
                        {
                            /**
                             * if route name opsway_quickbox - template is called for popup, mediaUrlMain will call popup controller method
                             * else - index controller method will be called
                             */
                            $configOptions = '';
                            if (Mage::app()->getStore()->isCurrentlySecure()) { //secure mode your current URL is HTTPS
                                $configOptions = array('id' => $simple->getId(), '_secure' => true);
                            } else { //your page is in HTTP mode
                                $configOptions = array('id' => $simple->getId());
                            }
                            if($this->getRequest()->getRouteName() == 'opsway_quickbox') {
                                $confDataSingle[$strKey]['media_url'] = $this->getUrl('amconf/media/popup', $configOptions);
                            } else {
                                $confDataSingle[$strKey]['media_url'] = $this->getUrl('amconf/media', $configOptions);
                            }
                            if(Mage::getStoreConfig('amconf/general/oneselect_reload')) {
                                $k = $strKey;
                                if(strpos($strKey, ',')){
                                    $k = substr($strKey, 0, strpos($strKey, ','));
                                }
                                if(!(array_key_exists($k, $confDataSingle) && array_key_exists('media_url', $confDataSingle[$k]))){
                                    $confDataSingle[$k]['media_url'] = $confDataSingle[$strKey]['media_url']; 
                                }
                            }
                            else{
                                //for changing only after first select 
                            }
                        } elseif ($noimgUrl) 
                        {
                            $confDataSingle[$strKey]['noimg_url'] = $noimgUrl;
                        }
                        //for >3
                        if(Mage::getStoreConfig('amconf/general/oneselect_reload')){
                            $pos = strpos($strKey, ",");
                            if($pos){
                                $pos = strpos($strKey, ",", $pos+1);
                                if($pos){
                                    $newKey = substr($strKey, 0, $pos);
                                    $confDataSingle[$newKey] =  $confDataSingle[$strKey];   
                                }
                            }
                            
                        }
                        
                    }
                }
                if (Mage::getStoreConfig('amconf/general/show_clear'))
                {
                    $html = '<a href="#" onclick="javascript: spConfig.clearConfig(); return false;">' . $this->__('Reset Configuration') . '</a>' . $html;
                }
                 
                /**
                 * if route name opsway_quickbox - template is called for popup, mediaUrlMain will call popup controller method
                 * else - index controller method will be called
                 */
                if($this->getRequest()->getRouteName() == 'opsway_quickbox') {
                    $mediaUrlMain = $this->getUrl('amconf/media/popup/', array('id' => $this->getProduct()->getId()));
                } else {
                    $mediaUrlMain = $this->getUrl('amconf/media', array('id' => $this->getProduct()->getId()));
                }
                 
                $html = '<script type="text/javascript">
                            var showAttributeTitle =' . intval(Mage::getStoreConfig('amconf/general/show_attribute_title')). '; 
                            var amConfAutoSelectAttribute = ' . intval(Mage::getStoreConfig('amconf/general/auto_select_attribute')) . ';
                            confDataSingle = new AmConfigurableData(' . Zend_Json::encode($confDataSingle) . ');
                            confDataSingle.textNotAvailable = "' . $this->__('Choose previous option please...') . '";
                            confDataSingle.mediaUrlMain = "' . $mediaUrlMain . '";
                            confDataSingle.oneAttributeReload = "' . (boolean) Mage::getStoreConfig('amconf/general/oneselect_reload') . '";
                            confDataSingle.useSimplePrice = "' . intval($_useSimplePrice)  . '";
                    </script>'. $html;
                
                if (Mage::getStoreConfig('amconf/general/hide_dropdowns'))
                {
                    $html .= '<script type="text/javascript">Event.observe(window, \'load\', spConfig.processEmpty);</script>';
                }              
            }
        }
        
        return $html;
    }
    
    protected function getImagesFromProductsAttributes(){
        $collection = Mage::getModel('amconf/product_attribute')->getCollection();
        $collection->addFieldToFilter('use_image_from_product', 1);
        
        $collection->getSelect()->join( array(
            'prodcut_super_attr' => $collection->getTable('catalog/product_super_attribute')),
                'main_table.product_super_attribute_id = prodcut_super_attr.product_super_attribute_id', 
                array('prodcut_super_attr.attribute_id')
            );
        
        $collection->addFieldToFilter('prodcut_super_attr.product_id', $this->getProduct()->getEntityId());
        
        
        $attributes = $collection->getItems();
        $ret = array();
        
        foreach($attributes as $attribute){
            $ret[] = $attribute->getAttributeId();
        }
        
        return $ret;
    }
    
    public function getJsonConfig()
    {
        $attributeIdsWithImages = array();
        $jsonConfig = parent::getJsonConfig();
        $config = Zend_Json::decode($jsonConfig);
        $productImagesAttributes = $this->getImagesFromProductsAttributes();
      
        foreach ($config['attributes'] as $attributeId => $attribute)
        {
            $attr = Mage::getModel('amconf/attribute')->load($attributeId, 'attribute_id');
            if ($attr->getUseImage())
            {
                $attributeIdsWithImages[] = $attributeId;
                $config['attributes'][$attributeId]['use_image'] = 1;
            }
                foreach ($attribute['options'] as $i => $option)
                {
                    $this->_optionProducts[$attributeId][$option['id']] = $option['products'];
                    if (in_array($attributeId, $productImagesAttributes)){
                        
                        foreach($option['products'] as $product_id){
        //                        
                            $size = Mage::getStoreConfig('amconf/product_image_size/thumb');
                            $product = Mage::getModel('catalog/product')->load($product_id);
                            $config['attributes'][$attributeId]['options'][$i]['image'] = 
                                (string)Mage::helper('catalog/image')->init($product, 'image')->resize($size);
                            break;
                        }
                    }
                    else if ($attr->getUseImage()){
                        $config['attributes'][$attributeId]['options'][$i]['image'] = Mage::helper('amconf')->getImageUrl($option['id']);
                    }
                }
        }
        Mage::unregister('amconf_images_attrids');
        Mage::register('amconf_images_attrids', $attributeIdsWithImages, true);

        return Zend_Json::encode($config);
    }
    
    public function getAddToCartUrl($product, $additional = array())
    {
        if ($this->hasCustomAddToCartUrl()) {
            return $this->getCustomAddToCartUrl();
        }
        if ($this->getRequest()->getParam('wishlist_next')){
            $additional['wishlist_next'] = 1;
        }
        $addUrlKey = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
        $addUrlValue = Mage::getUrl('*/*/*', array('_use_rewrite' => true, '_current' => true));
        $additional[$addUrlKey] = Mage::helper('core')->urlEncode($addUrlValue);
        return $this->helper('checkout/cart')->getAddUrl($product, $additional);
    }
    
    public function isSalable($product = null){
         $salable = parent::isSalable($product);
 
        if ($salable !== false) {
            $salable = false;
            if (!is_null($product)) {
                $this->setStoreFilter($product->getStoreId(), $product);
            }
 
            if (!Mage::app()->getStore()->isAdmin() && $product) {
                $collection = $this->getUsedProductCollection($product)
                    ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->setPageSize(1)
                    ;
                if ($collection->getFirstItem()->getId()) {
                    $salable = true;
                }
            } else {
                foreach ($this->getUsedProducts(null, $product) as $child) {
                    if ($child->isSalable()) {
                        $salable = true;
                        break;
                    }
                }
            }
        }
 
        return $salable;
    }
   
}