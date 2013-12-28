<?php
/**
 * Audere Commerce
 *
 * NOTICE OF LICENCE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customise this module for your
 * needs please contact Audere Commerce (http://www.auderecommerce.com).
 *
 * @category    AudereCommerce
 * @package     AudereCommerce_ProCategory
 * @copyright   Copyright (c) 2013 Audere Commerce Limited. (http://www.auderecommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      James Withers <james.withers@auderecommerce.com>
 */

final class AudereCommerce_ProCategory_Model_Rule extends Mage_Rule_Model_Abstract
{
    const XML_NODE_RELATED_CACHE = 'global/auderecommerce_procategory/related_cache_types';
    
    protected $_productIds;
    protected $_productsFilter = null;
    
    final protected function _construct()
    {
        $this->_init('auderecommerce_procategory/rule');
        return $this;
    }
    
    public function setProductsFilter($productIds)
    {
        $this->_productsFilter = $productIds;
    }

    public function getProductsFilter()
    {
        return $this->_productsFilter;
    }    
    
    /**
     * @return AudereCommerce_ProCategory_Model_Rule_Condition_Combine
     */
    final public function getConditionsInstance()
    {
        return Mage::getModel('auderecommerce_procategory/rule_condition_combine');
    }

    /**
     * @return AudereCommerce_ProCategory_Model_Rule_Action_Collection
     */
    final public function getActionsInstance()
    {
        return Mage::getModel('auderecommerce_procategory/rule_action_collection');
    }
    
    final public function getCategoryIds()
    {
        if ($categoryIds = $this->getData('category_ids')) {
            return array_unique($categoryIds);
        } else {
            return $this->_getResource()->getCategoryIds($this);
        }
    }
    
    final public function validateData(Varien_Object $object)
    {
        $result = parent::validateData($object);
        if (!$object->getData('category_ids')) {
            if ($result === true) {
                $result = array();
            }
            $result[] = Mage::helper('auderecommerce_procategory')->__('At least one category needs to be selected');
        }
        return $result;
    }
    
    final public function loadPost(array $data)
    {
        parent::loadPost($data);
        $this->_loadPostCategoryIds($data);
        return $this;
    }    
    
    final protected function _loadPostCategoryIds(array $postData)
    {
        $categoryIds = trim($postData['category_ids'], ',');
        $this->setCategoryIds(explode(',', $categoryIds));
        return $this;        
    }

    final public function getMatchingProductIds()
    {
        if (is_null($this->_productIds)) {            
            $this->_productIds = array();
            $this->setCollectedAttributes(array());
        
            $products = Mage::getModel('catalog/product')->getCollection();
            /* @var $products Mage_Catalog_Model_Resource_Product_Collection */
            if ($this->_productsFilter) {                
                $products->addIdFilter($this->_productsFilter);
            }
            $this->getConditions()->collectValidatedAttributes($products);
            if ($this->getFilterNew()) {
                $products = $this->_filterNew($products);
            }
            if ($this->getFilterSale()) {
                $products = $this->_filterSale($products);
            } 
            Mage::getSingleton('core/resource_iterator')->walk(
                $products->getSelect(),
                array(array($this, 'callbackValidateProduct')),
                array(
                    'attributes' => $this->getCollectedAttributes(),
                    'product'    => Mage::getModel('catalog/product'),
                )
            );  
        }
        $limit = (int)$this->getLimit();
        if ($limit > 0) {
            $this->_productIds = array_slice($this->_productIds, 0, $limit);
        } 
        return $this->_productIds;
    }    
    
    final protected function _filterNew(Mage_Catalog_Model_Resource_Product_Collection $products)
    {
        return $this->_filterProductsByDateRange($products, 'news_from_date', 'news_to_date');
    }
    
    final protected function _filterSale(Mage_Catalog_Model_Resource_Product_Collection $products)
    {
        return $this->_filterProductsByDateRange($products, 'special_from_date', 'special_to_date');
    }
    
    final protected function _filterProductsByDateRange(Mage_Catalog_Model_Resource_Product_Collection $products, $fromAttr, $toAttr)
    {
        $todayStartOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $todayEndOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('23:59:59')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        
        $products->addAttributeToFilter($fromAttr, array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter($toAttr, array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => $fromAttr, 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => $toAttr, 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToSort($fromAttr, 'desc');
        return $products;
    }
    
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);

        if ($this->getConditions()->validate($product)) {
            $this->_productIds[] = $product->getId();
        }
    }
    
    final public function applyAll()
    {
        $this->getResourceCollection()->walk(array($this->_getResource(), 'updateRuleProCategoryData'));
        $this->_invalidateCache();
        $indexProcess = Mage::getSingleton('index/indexer')->getProcessByCode('catalog_category_product');
        if ($indexProcess) {
            $indexProcess->reindexAll();
        }
    }   
    
    final protected function _invalidateCache()
    {
        $types = Mage::getConfig()->getNode(self::XML_NODE_RELATED_CACHE);
        if ($types) {
            $types = $types->asArray();
            Mage::app()->getCacheInstance()->invalidateType(array_keys($types));
        }
        return $this;
    }
    
    final public function applyToCategory(Mage_Catalog_Model_Category $category)
    {
        $this->setCategoryIds(array($category->getId()));
        if ($this->getIsActive()) {            
            $this->getResource()->updateRuleProCategoryData($this);
            $this->_invalidateCache();
        }
        return $this;
    }
    
    final public function applyToProduct(Mage_Catalog_Model_Product $product)
    {
        $this->setProductsFilter($product->getId());
        if ($this->getIsActive()) {            
            $this->getResource()->updateRuleProCategoryData($this);
            $this->_invalidateCache();
        }
        return $this;
    }
}