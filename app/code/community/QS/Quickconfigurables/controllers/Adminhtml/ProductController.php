<?php
/**
 * Automatic Configurables Extension
 *
 * @category   QS
 * @package    QS_Quickconfigurables
 * @author     Quart-soft Magento Team <magento@quart-soft.com> 
 * @copyright  Copyright (c) 2010 Quart-soft Ltd http://quart-soft.com
 */

class QS_Quickconfigurables_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{
    public function simpleAction()
	{
		$post = $this->getRequest()->getPost();
		
        /* @var $configurableProduct Mage_Catalog_Model_Product */
        $configurableProduct = Mage::getModel('catalog/product')
            ->setStoreId(0)
            ->load($post['product_id']);

        if (!$configurableProduct->isConfigurable()) {
            // If invalid parent product
            $errors['Wrong parent product'];
			$this->getResponse()->setBody(Zend_Json::encode($errors));	
            return;
        }
		
		try {
			/* @var $product Mage_Catalog_Model_Product */
			$product = Mage::getModel('catalog/product')
				->setStoreId(0)
				->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
				->setAttributeSetId($configurableProduct->getAttributeSetId());


			foreach ($product->getTypeInstance()->getEditableAttributes() as $attribute) {
				if ($attribute->getIsUnique()
					|| $attribute->getFrontend()->getInputType() == 'gallery'
					|| $attribute->getFrontend()->getInputType() == 'media_image'
					|| !$attribute->getIsVisible()) {
					continue;
				}

				$product->setData(
					$attribute->getAttributeCode(),
					$configurableProduct->getData($attribute->getAttributeCode())
				);
			}

			$product->addData($this->_getSession()->getData('combination_form_data'));
			
			$codesToSet = explode(':',$post['codes']);
			$valuesToSet = explode(':',$post['values']);
			$i = 0;
			foreach ($codesToSet as $codeToSet ){
				$product->setData(
						$codeToSet,
						$valuesToSet[$i++]		
					);
			}
			
			$product->setWebsiteIds($configurableProduct->getWebsiteIds());

			$autogenerateOptions = array();

			foreach ($configurableProduct->getTypeInstance()->getConfigurableAttributes() as $attribute) {
				$value = $product->getAttributeText($attribute->getProductAttribute()->getAttributeCode());
				$autogenerateOptions[] = $value;
				$result['attributes'][] = array(
					'label'         => $value,
					'value_index'   => $product->getData($attribute->getProductAttribute()->getAttributeCode()),
					'attribute_id'  => $attribute->getProductAttribute()->getId()
				);
			}

			if ($product->getNameAutogenerate()) {
				$product->setName($configurableProduct->getName() . '-' . implode('-', $autogenerateOptions));
			}

			if ($product->getSkuAutogenerate()) {
				$product->setSku($configurableProduct->getSku() . '-' . implode('-', $autogenerateOptions));
			}

			if (is_array($product->getPricing())) {
			   $result['pricing'] = $product->getPricing();
			   $additionalPrice = 0;
			   foreach ($product->getPricing() as $pricing) {
				   if (empty($pricing['value'])) {
					   continue;
				   }

				   if (!empty($pricing['is_percent'])) {
					   $pricing['value'] = ($pricing['value']/100)*$product->getPrice();
				   }

				   $additionalPrice += $pricing['value'];
			   }

			   $product->setPrice($product->getPrice() + $additionalPrice);
			   $product->unsPricing();
			}

			$success = true;
			$product->validate();
			$product->save();
			$result['product_ids'][] = $product->getId();
		} catch (Mage_Core_Exception $e) {
			$result['error'] = array(
				'message' =>  $e->getMessage(),
				'fields'  => array(
				'sku'  =>  $product->getSku()
				)
			);

		} catch (Exception $e) {
			Mage::logException($e);
			$result['error'] = array(
				'message'   =>  $this->__('Product saving error. ') . $e->getMessage()
			 );
		}		
		
		try {
			$this->getResponse()->setBody(Zend_Json::encode($result));
		} catch (Exception $e) {
			Mage::logException($e);
		}		
		
	}
	
	public function combinationAction()
    {
        $result = array();
		$errors = false;
		$success = false;
		
        /* @var $configurableProduct Mage_Catalog_Model_Product */
        $configurableProduct = Mage::getModel('catalog/product')
            ->setStoreId(0)
            ->load($this->getRequest()->getParam('product'));

        if (!$configurableProduct->isConfigurable()) {
            // If invalid parent product
            $errors[] = 'Wrong parent product';
			$this->getResponse()->setBody(Zend_Json::encode($errors));	
            return;
        }
		
		$postData = $this->getRequest()->getParam('combination', array());
		
		$this->_getSession()->setData('combination_form_data', $postData);
		
		$attributeValues = array();
		$requiredAttributesIds = explode(',',$this->getRequest()->getParam('required'));
		foreach ($requiredAttributesIds as $requiredAttributeId){
			$attributeData = Mage::getModel('eav/config')->getAttribute('catalog_product', $requiredAttributeId);
				foreach ($postData[$attributeData->getAttributeCode()] as $option){
					if ($option){
						$attributeValues[$attributeData->getAttributeCode()][] = $option;
					}
				}
		}
		
		$combinationsTemp = array();
		$combinationsFinal = array();
		$i = 0;
		
		foreach($attributeValues as $code=>$values){
			$combinationsFinal = array();
			if ($combinationsTemp){
				foreach ($combinationsTemp as $tmpComb){
					foreach ($values as $value){
						$combinationsFinal[] = array(
							'codes' => $tmpComb['codes'] . ':' . $code,
							'values' => $tmpComb['values'] . ':' . $value,
						);
					}
				}
			} else {
				foreach ($values as $value){
					$combinationsFinal[] = array(
						'codes' => $code,
						'values' => $value,  
					);
				}
			}
			$combinationsTemp = $combinationsFinal;
		}
		
		if ($combinationsFinal){
			$result['combinations'] = $combinationsFinal;
			$result['product_id'] = $configurableProduct->getId();
			$this->getResponse()->setBody(Zend_Json::encode($result));		
		} else {
			Mage::logException($e);
		}
		
    }		
}