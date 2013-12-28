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

final class AudereCommerce_ProCategory_Model_Observer_Adminhtml extends Mage_Core_Model_Abstract
{
    final public function adminhtmlCatalogCategoryTabs(Varien_Event_Observer $observer)
    {
        $this->_addProCategoryRuleTab($observer->getEvent()->getTabs());        
        return $this;
    }
    
    final protected function _addProCategoryRuleTab(Mage_Adminhtml_Block_Catalog_Category_Tabs $tabs)
    {
        $tabs->addTab('auderecommerce_procategory_rule', array(
            'label' => Mage::helper('auderecommerce_procategory')->__('ProCategory Rule'),
            'content'   => $tabs->getLayout()->createBlock(
                'auderecommerce_procategory/catalog_category_tab_rule',
                'auderecommerce_catalogcategory_category_rule'
            )->toHtml()
        ));
        return $this;
    }
   
    final public function catalogCategorySaveAfter(Varien_Event_Observer $observer)
    {
        $category = $observer->getEvent()->getCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $this->_applyAllRulesToCategory($category);
        return $this;
    }
    
    final protected function _applyAllRulesToCategory(Mage_Catalog_Model_Category $category)
    {
        foreach ($this->_getRules() as $rule) {
            /* @var $rule AudereCommerce_ProCategory_Model_Rule */
            $rule->applyToCategory($category);
        }
        return $this;
    }
    
    /**
     * @return AudereCommerce_CatalogProduct_Model_Resource_Rule_Collection
     */
    final protected function _getRules()
    {
        return Mage::getModel('auderecommerce_procategory/rule')->getCollection()
                ->addFieldToFilter('is_active', 1);
    }
    
    final public function catalogProductSaveAfter(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        /* @var $product Mage_Catalog_Model_Product */
        $this->_applyAllRulesToProduct($product);
        return $this;
    }
    
    final protected function _applyAllRulesToProduct(Mage_Catalog_Model_Product $product)
    {
        foreach ($this->_getRules() as $rule) {
            /* @var $rule AudereCommerce_ProCategory_Model_Rule */
            $rule->applyToProduct($product);
        }
        return $this;        
    }
    
    public function catalogEntityAttributeSaveAfter(Varien_Event_Observer $observer)
    {
        $attribute = $observer->getEvent()->getAttribute();
        if ($attribute->dataHasChangedFor('is_used_for_promo_rules') && !$attribute->getIsUsedForPromoRules()) {
            $this->_checkCatalogRulesAvailability($attribute->getAttributeCode());
        }
        return $this;
    }

    public function catalogEntityAttributeDeleteAfter(Varien_Event_Observer $observer)
    {
        $attribute = $observer->getEvent()->getAttribute();
        if ($attribute->getIsUsedForPromoRules()) {
            $this->_checkCatalogRulesAvailability($attribute->getAttributeCode());
        }
        return $this;
    }  
    
    protected function _checkCatalogRulesAvailability($attributeCode)
    {
        /* @var $collection AudereCommerce_ProCategory_Model_Mysql4_Rule_Collection */
        $collection = Mage::getResourceModel('auderecommerce_procategory/rule_collection')
            ->addAttributeInConditionFilter($attributeCode);

        $disabledRulesCount = 0;
        foreach ($collection as $rule) {
            /* @var $rule AudereCommerce_ProCategory_Model_Rule */
            $rule->setIsActive(0);
            /* @var $rule->getConditions() AudereCommerce_ProCategory_Model_Rule_Condition_Combine */
            $this->_removeAttributeFromConditions($rule->getConditions(), $attributeCode);
            $rule->save();

            $disabledRulesCount++;
        }

        if ($disabledRulesCount) {
            Mage::getModel('auderecommerce_procategory/rule')->applyAll();
            Mage::getSingleton('adminhtml/session')->addWarning(
                Mage::helper('auderecommerce_procategory')->__('%d ProCategory Rules based on "%s" attribute have been disabled.', $disabledRulesCount, $attributeCode));
        }

        return $this;
    }

    protected function _removeAttributeFromConditions($combine, $attributeCode)
    {
        $conditions = $combine->getConditions();
        foreach ($conditions as $conditionId => $condition) {
            if ($condition instanceof AudereCommerce_ProCategory_Model_Rule_Condition_Combine) {
                $this->_removeAttributeFromConditions($condition, $attributeCode);
            }
            if ($condition instanceof Mage_Rule_Model_Condition_Product_Abstract) {
                if ($condition->getAttribute() == $attributeCode) {
                    unset($conditions[$conditionId]);
                }
            }
        }
        $combine->setConditions($conditions);
    }    
}