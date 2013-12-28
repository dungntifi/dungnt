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

final class AudereCommerce_ProCategory_Model_Resource_Rule
    extends Mage_Rule_Model_Resource_Abstract
{
    final protected function _construct()
    {
        $this->_init('auderecommerce_procategory/rule', 'rule_id');
        return $this;
    }
        
    final protected function _afterSave(Mage_Core_Model_Abstract $rule)
    {
        parent::_afterSave($rule);
        $adapter = $this->_getWriteAdapter();        
        $adapter->beginTransaction();
        try {
            $table = $this->getTable('auderecommerce_procategory/category_product');
            $adapter->delete($table, $adapter->quoteInto('rule_id = ?', $rule->getRuleId()));
            foreach ($rule->getCategoryIds() as $categoryId) {                
                $adapter->insertOnDuplicate($table, array(
                    'rule_id' => $rule->getRuleId(),
                    'category_id' => $categoryId
                ));
            }
            $adapter->commit();
        } catch (Exception $ex) {
            $adapter->rollBack();
            throw $ex;
        }        
        return $this;
    }
    
    final public function getCategoryIds(AudereCommerce_ProCategory_Model_Rule $rule)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                ->distinct()
                ->from($this->getTable('auderecommerce_procategory/category_product'), 'category_id')
                ->where('rule_id = ?', $rule->getRuleId());
        $categoryIds = array();
        foreach ($adapter->fetchAll($select) as $row) {
            $categoryIds[] = $row['category_id'];
        }
        return $categoryIds;
    }
    
    final public function updateRuleProCategoryData(AudereCommerce_ProCategory_Model_Rule $rule)
    {
        if (!$rule->getIsActive()) {
            return $this;
        }
       
        $ruleId = $rule->getRuleId();
        $write  = $this->_getWriteAdapter();
        $table = $this->getTable('auderecommerce_procategory/category_product');
        $categoryProductTable = $this->getTable('catalog/category_product');
        $categoryIds = $rule->getCategoryIds();        
        $productIds = $rule->getMatchingProductIds();
        $productsFilter = $rule->getProductsFilter();
               
        $write->beginTransaction();
        try {
            if ($productsFilter) {
                $write->delete($table, array('rule_id = ?' => $ruleId, 'product_id IN (?)' => $productsFilter));
            } else {
                $write->delete($table, $write->quoteInto('rule_id = ? AND product_id IS NOT NULL', $ruleId));
            }            
            if ($rule->getStrict()) {
                if ($productsFilter) {
                    $write->delete($categoryProductTable, $write->quoteInto('category_id IN (?) AND product_id IN (?)', array($categoryIds, $productsFilter)));
                } else {
                    $write->delete($categoryProductTable, $write->quoteInto('category_id IN (?)', $categoryIds));
                }
            }      
            if (!empty($productIds)) {
                foreach ($categoryIds as $categoryId) {
                    foreach ($productIds as $productId) {
                        $write->insertOnDuplicate($categoryProductTable, array(
                            'product_id' => $productId,
                            'category_id' => $categoryId
                        ));
                        $write->insertOnDuplicate($table, array(
                            'rule_id' => $ruleId,
                            'product_id' => $productId,
                            'category_id' => $categoryId
                        ));                        
                    }
                }
            }            
            $write->commit();
        } catch (Exception $e) {
            $write->rollBack();
            throw $e;
        }
        return $this;
    } 
}