<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Conf
*/
class Amasty_Conf_Block_Catalog_Product_View_Type_Configurablel extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    protected $_optionProducts;
    protected $_jsonConfig;
    
    protected function _afterToHtml($html)
    {

        $imageSizeAtCategoryPage = Mage::getStoreConfig('amconf/list/main_image_list_size');
        if(strpos($imageSizeAtCategoryPage, ',') !== false) {
            $imageSizeAtCategoryPage = explode(',', $imageSizeAtCategoryPage);
        }
        $html = parent::_afterToHtml($html);
        if ('product.info.options.configurable' == $this->getNameInLayout())
        {
            
            $html = str_replace('super-attribute-select', 'no-display super-attribute-select', $html);
           
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
                        $confData[$strKey] = array();

                        $gallery = Mage::getModel('catalog/product')->load($simple->getId())->getMediaGalleryImages(); 
                        if(!('no_selection' == $simple->getSmallImage() || '' == $simple->getSmallImage())){
                            if(is_array($imageSizeAtCategoryPage)) {
                                $j = 1; $imagesArr = '';
                                foreach ($gallery as $_image) : 
                                    if($j <= 2) : 
                                        $imagesArr[] = (string)($this->helper('catalog/image')->init($simple, 'small_image', $_image->getFile())->resize($imageSizeAtCategoryPage[0], $imageSizeAtCategoryPage[1]));
                                    $j++; endif; 
                                endforeach;
                                $confData[$strKey]['small_image'] = $imagesArr;
                                $confData[$strKey]['parent_image'] = (string)($this->helper('catalog/image')->init($this->getProduct(), 'small_image')->resize($imageSizeAtCategoryPage[0], $imageSizeAtCategoryPage[1]));
                            } else {
                                $j = 1; $imagesArr = '';
                                foreach ($gallery as $_image) : 
                                    if($j <= 2) : 
                                        $imagesArr[] = (string)($this->helper('catalog/image')->init($simple, 'small_image', $_image->getFile())->resize($imageSizeAtCategoryPage));
                                    $j++; endif; 
                                endforeach;
                                $confData[$strKey]['small_image'] = $imagesArr;
                                $confData[$strKey]['parent_image'] = (string)($this->helper('catalog/image')->init($this->getProduct(), 'small_image')->resize($imageSizeAtCategoryPage));
                            }
                            if(Mage::getStoreConfig('amconf/general/oneselect_reload')) {
                                $k = $strKey;
                                if(strpos($strKey, ',') > 0){
                                    $k = substr($strKey, 0, strpos($strKey, ','));
                                }
                                if(!(array_key_exists($k, $confData) && array_key_exists('small_image', $confData[$k]))){
                                    $confData[$k]['small_image'] = $confData[$strKey]['small_image'];
                                    $confData[$k]['parent_image'] = $confData[$strKey]['parent_image']; 
                                }
                            }
                            else{
                               //for only first 
                            }
                        }
                        else{
                            if(is_array($imageSizeAtCategoryPage)) {
                                $j = 1; $imagesArr = '';
                                foreach ($gallery as $_image) : 
                                    if($j <= 2) : 
                                        $imagesArr[] = (string)($this->helper('catalog/image')->init($this->getProduct(), 'small_image', $_image->getFile())->resize($imageSizeAtCategoryPage[0], $imageSizeAtCategoryPage[1]));
                                    $j++; endif; 
                                endforeach;
                                $confData[$strKey]['small_image'] = $imagesArr;
                                $confData[$strKey]['parent_image'] = (string)($this->helper('catalog/image')->init($this->getProduct(), 'small_image')->resize($imageSizeAtCategoryPage[0], $imageSizeAtCategoryPage[1])); 
                            } else {
                                $j = 1; $imagesArr = '';
                                foreach ($gallery as $_image) : 
                                    if($j <= 2) : 
                                        $imagesArr[] = (string)($this->helper('catalog/image')->init($this->getProduct(), 'small_image', $_image->getFile())->resize($imageSizeAtCategoryPage));
                                    $j++; endif; 
                                endforeach;
                                $confData[$strKey]['small_image'] = $imagesArr; 
                                $confData[$strKey]['parent_image'] = (string)($this->helper('catalog/image')->init($this->getProduct(), 'small_image')->resize($imageSizeAtCategoryPage)); 
                            }
                        }
                        
                        if (Mage::getStoreConfig('amconf/general/reload_name'))
                        {
                            $confData[$strKey]['name'] = $simple->getName();
                        }
                 
                        // the html blocks are required for product view page
                        if ($_useSimplePrice)
                        {
                            $tierPriceHtml = $this->getTierPriceHtml($simple);
                            $confData[$strKey]['price_html'] = $this->getPriceHtml($simple) . $tierPriceHtml;
                            $confData[$strKey]['price_clone_html'] = $this->getPriceHtml($simple, false, '_clone') . $tierPriceHtml;
                        }
                        //for >3
                        if(Mage::getStoreConfig('amconf/general/oneselect_reload')){
                            $pos = strpos($strKey, ",");
                            if($pos){
                                $pos = strpos($strKey, ",", $pos+1);
                                if($pos){
                                    $newKey = substr($strKey, 0, $pos);
                                    $confData[$newKey] =  $confData[$strKey];   
                                }
                            }
                            
                        }
                        
                    }
                }
                $html = '<script type="text/javascript"> 
                              confData['.$this->getProduct()->getEntityId().'] = new AmConfigurableData(' . Zend_Json::encode($confData) . ');
                              confData['.$this->getProduct()->getEntityId().'].textNotAvailable = "' . $this->__('Choose previous option please...') . '";
                              confData['.$this->getProduct()->getEntityId().'].oneAttributeReload = "' . (boolean) Mage::getStoreConfig('amconf/general/oneselect_reload') . '";
                              confData['.$this->getProduct()->getEntityId().'].useSimplePrice = "' . intval($_useSimplePrice)  . '";
                              amRequaredField = "' .  $this->__('&uarr;  This is a required field.') . '";
                </script>' . $html;
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
        $jsonConfig = parent::getJsonConfig();
        $config = Zend_Json::decode($jsonConfig);
        $productImagesAttributes = $this->getImagesFromProductsAttributes();
      
        if (Mage::helper('amconf')->getOptionsImageSize()){
             $config['size'] = Mage::helper('amconf')->getOptionsImageSize();
        }

        $ids = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($this->getProduct()->getId());   
        $simpleProducts = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('entity_id', $ids)
            ->addAttributeToSelect('color')
            ->addAttributeToSelect('color_position')
            ->addAttributeToSort('color_position', 'ASC');

        $colorPosition = '';
        foreach ($simpleProducts as $key => $value) {
            $colorPosition[$value->getColor()] = '';
        }

        foreach ($config['attributes'] as $attributeId => $attribute)
        {
            $attr = Mage::getModel('amconf/attribute')->load($attributeId, 'attribute_id');
            //set image from simple product
            if (in_array($attributeId, $productImagesAttributes) && $attr->getUseImage()){
                foreach ($attribute['options'] as $i => $option)
                {
                    $this->_optionProducts[$attributeId][$option['id']] = $option['products'];
                    $config['attributes'][$attributeId]['use_image'] = 1;
                    foreach($option['products'] as $product_id){
                       
                        $size = Mage::helper('amconf')->getOptionsImageSize();
                        $product = Mage::getModel('catalog/product')->load($product_id);
                        $config['attributes'][$attributeId]['options'][$i]['image'] = 
                            (string)Mage::helper('catalog/image')->init($product, 'image')->resize($size);
                        break;
                    }
                }        
            }
            else if($attr->getUseImage()){
                foreach ($attribute['options'] as $i => $option)
                {
                    $this->_optionProducts[$attributeId][$option['id']] = $option['products'];
                    $config['attributes'][$attributeId]['use_image'] = 1;
                    $config['attributes'][$attributeId]['options'][$i]['image'] = Mage::helper('amconf')->getImageUrl($option['id']);
                }    
            }
        }

        $newColorOptions = $newSizeOptions = '';
        foreach ($config['attributes'] as $attributeId => $attribute)
        {
            foreach ($colorPosition as $color => $value) {
                foreach ($attribute['options'] as $i => $option) {
                    if($option['id'] == $color) {
                        $newColorOptions[] = $option;
                        $newSizeOptions[] = $config['attributes'][151]['options'][$i];
                    }
                }
            }   
        }

        if(is_array($newColorOptions)) {
            unset($config['attributes'][92]['options']); //removing old color options
            $config['attributes'][92]['options'] = $newColorOptions; //adding color options with new sort order
        }
        if(is_array($newSizeOptions)) {
            unset($config['attributes'][151]['options']); //removing old size options
            $config['attributes'][151]['options'] = $newSizeOptions; //adding color options with new sort order
        }

        $this->_jsonConfig = $config;
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
    
     public function getAttributes() {
        $data = $this->_jsonConfig;
        if(array_key_exists("attributes", $data))
            $attributes = $data['attributes'];
        $keys = array();
        foreach($attributes as $key=>$attribute){
            $keys[] = $key;
        }
        return Zend_Json::encode($keys);
    } 
    
    public function getPriceJsonConfig()
    {
        $config = array();
        $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        $product = $this->product; 
        $_request->setProductClassId($product->getTaxClassId());
        $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
        $_request->setProductClassId($product->getTaxClassId());
        $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_regularPrice = $product->getPrice();
        $_finalPrice = $product->getFinalPrice();
        $_priceInclTax = Mage::helper('tax')->getPrice($product, $_finalPrice, true);
        $_priceExclTax = Mage::helper('tax')->getPrice($product, $_finalPrice);
        $_tierPrices = array();
        $_tierPricesInclTax = array();
        foreach ($product->getTierPrice() as $tierPrice) {
            $_tierPrices[] = Mage::helper('core')->currency($tierPrice['website_price'], false, false);
            $_tierPricesInclTax[] = Mage::helper('core')->currency(
                Mage::helper('tax')->getPrice($product, (int)$tierPrice['website_price'], true),
                false, false);
        }
        $config = array(
            'productId'           => $product->getId(),
            'priceFormat'         => Mage::app()->getLocale()->getJsPriceFormat(),
            'includeTax'          => Mage::helper('tax')->priceIncludesTax() ? 'true' : 'false',
            'showIncludeTax'      => Mage::helper('tax')->displayPriceIncludingTax(),
            'showBothPrices'      => Mage::helper('tax')->displayBothPrices(),
            'productPrice'        => Mage::helper('core')->currency($_finalPrice, false, false),
            'productOldPrice'     => Mage::helper('core')->currency($_regularPrice, false, false),
            'priceInclTax'        => Mage::helper('core')->currency($_priceInclTax, false, false),
            'priceExclTax'        => Mage::helper('core')->currency($_priceExclTax, false, false),
            'skipCalculate'       => ($_priceExclTax != $_priceInclTax ? 0 : 1),
            'defaultTax'          => $defaultTax,
            'currentTax'          => $currentTax,
            'idSuffix'            => '_clone',
            'oldPlusDisposition'  => 0,
            'plusDisposition'     => 0,
            'plusDispositionTax'  => 0,
            'oldMinusDisposition' => 0,
            'minusDisposition'    => 0,
            'tierPrices'          => $_tierPrices,
            'tierPricesInclTax'   => $_tierPricesInclTax,
        );

        $responseObject = new Varien_Object();
        Mage::dispatchEvent('catalog_product_view_config', array('response_object'=>$responseObject));
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option=>$value) {
                $config[$option] = $value;
            }
        }

        return Mage::helper('core')->jsonEncode($config);
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