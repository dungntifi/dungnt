<?php
/**
 * Automatic Configurables Extension
 *
 * @category   QS
 * @package    QS_Quickconfigurables
 * @author     Quart-soft Magento Team <magento@quart-soft.com> 
 * @copyright  Copyright (c) 2010 Quart-soft Ltd http://quart-soft.com
 */
class QS_Quickconfigurables_Block_Adminhtml_Catalog_Product_Edit_Tab_Super_Config_Combination extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $form->setFieldNameSuffix('combination');

        $fieldset = $form->addFieldset('combination', array(
            'legend' => Mage::helper('quickconfigurables')->__('Create Combinations in One Click (Automatic Configurables)')
        ));
        $this->_addElementTypes($fieldset);
        $attributesConfig = array(
            'autogenerate' => array('name'),
			'nonedit'	   => array('sku'),
            'additional'   => array('name', 'sku', 'visibility', 'status')
        );

        $availableTypes = array('text', 'select', 'multiselect', 'textarea', 'price');

        $attributes = Mage::getModel('catalog/product')
            ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
            ->setAttributeSetId($this->_getProduct()->getAttributeSetId())
            ->getAttributes();

        /* Standart attributes */
        foreach ($attributes as $attribute) {
            if (($attribute->getIsRequired()
                && $attribute->getApplyTo()
                // If not applied to configurable
                && !in_array(Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE, $attribute->getApplyTo())
                // If not used in configurable
                && !in_array($attribute->getId(),$this->_getProduct()->getTypeInstance(true)->getUsedProductAttributeIds($this->_getProduct())))
                // Or in additional
                || in_array($attribute->getAttributeCode(), $attributesConfig['additional'])) {

                $inputType = $attribute->getFrontend()->getInputType();
                if (!in_array($inputType, $availableTypes)) {
                    continue;
                }
                $attributeCode = $attribute->getAttributeCode();
                $element = $fieldset->addField(
                    'combination_' . $attributeCode,
                     $inputType,
                     array(
                        'label'    => $attribute->getFrontend()->getLabel(),
                        'name'     => $attributeCode,
                        'required' => $attribute->getIsRequired(),
                     )
                )->setEntityAttribute($attribute);

                if (in_array($attributeCode, $attributesConfig['autogenerate'])) {
                    $element->setDisabled('true');
                    $element->setValue($this->_getProduct()->getData($attributeCode));
                    $element->setAfterElementHtml(
                         '<input type="checkbox" id="combination_' . $attributeCode . '_autogenerate" '
                         . 'name="combination[' . $attributeCode . '_autogenerate]" value="1" '
                         . 'onclick="toggleValueElements(this, this.parentNode)" checked="checked" /> '
                         . '<label for="combination_' . $attributeCode . '_autogenerate" >'
                         . Mage::helper('catalog')->__('Autogenerate')
                         . '</label>'
                    );
                }
				
                if (in_array($attributeCode, $attributesConfig['nonedit'])) {
                    $element->setDisabled('true');
                    $element->setValue(Mage::helper('quickconfigurables')->__('Attribute value will be autogenerated'));
                    $element->setAfterElementHtml(
                         '<input type="hidden" id="combination_' . $attributeCode . '_autogenerate" '
                         . 'name="combination[' . $attributeCode . '_autogenerate]" value="1" '
                    );					
                }				

                if ($inputType == 'select' || $inputType == 'multiselect') {
                    $element->setValues($attribute->getFrontend()->getSelectOptions());
                }
            }
        }
		
        /* Configurable attributes */
        foreach ($this->_getProduct()->getTypeInstance(true)->getUsedProductAttributes($this->_getProduct()) as $attribute) {
            $attributeCode =  $attribute->getAttributeCode();
            $fieldset->addField( 'combination_' . $attributeCode, 'multiselect',  array(
                'label' => $attribute->getFrontend()->getLabel(),
                'name'  => $attributeCode,
                'values' => $attribute->getSource()->getAllOptions(false, true),
                'required' => true,
                'class'    => 'validate-configurable',
            ));
		}		

        /* Inventory Data */
        $fieldset->addField('combination_inventory_qty', 'text', array(
            'label' => Mage::helper('catalog')->__('Qty'),
            'name'  => 'stock_data[qty]',
            'class' => 'validate-number',
            'required' => true,
            'value'  => 0
        ));

        $fieldset->addField('combination_inventory_is_in_stock', 'select', array(
            'label' => Mage::helper('catalog')->__('Stock Availability'),
            'name'  => 'stock_data[is_in_stock]',
            'values' => array(
                array('value'=>1, 'label'=> Mage::helper('catalog')->__('In Stock')),
                array('value'=>0, 'label'=> Mage::helper('catalog')->__('Out of Stock'))
            ),
            'value' => 1
        ));

        $stockHiddenFields = array(
            'use_config_min_qty'            => 1,
            'use_config_min_sale_qty'       => 1,
            'use_config_max_sale_qty'       => 1,
            'use_config_backorders'         => 1,
            'use_config_notify_stock_qty'   => 1,
            'is_qty_decimal'                => 0
        );

        foreach ($stockHiddenFields as $fieldName=>$fieldValue) {
            $fieldset->addField('combination_inventory_' . $fieldName, 'hidden', array(
                'name'  => 'stock_data[' . $fieldName .']',
                'value' => $fieldValue
            ));
        }


        $fieldset->addField('create_button', 'note', array(
            'text' => $this->getButtonHtml(
                Mage::helper('catalog')->__('Create Combinations'),
                //'superProduct.createPopup(createCombinationsUrl)',
				'superProduct.quickCombinations()',
                'save'
            )
        ));
		
        $this->setForm($form);
    }

    /**
     * Retrieve currently edited product object
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }
} 