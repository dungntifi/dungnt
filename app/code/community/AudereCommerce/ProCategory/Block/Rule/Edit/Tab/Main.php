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

final class AudereCommerce_ProCategory_Block_Rule_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    final public function getTabLabel()
    {
        return Mage::helper('auderecommerce_procategory')->__('Rule Information');
    }

    final public function getTabTitle()
    {
        return Mage::helper('auderecommerce_procategory')->__('Rule Information');
    }

    final public function canShowTab()
    {
        return true;
    }

    final public function isHidden()
    {
        return false;
    }

    final protected function _prepareForm()
    {
        $rule = Mage::registry('current_auderecommerce_procategory_rule');
        /* @var $rule AudereCommerce_ProCategory_Model_Rule */
        $helper = Mage::helper('auderecommerce_procategory');
        /* @var $helper AudereCommerce_ProCategory_Helper_Data */
        
        
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend '=> $helper->__('General Information'))
        );

        $fieldset->addField('auto_apply', 'hidden', array(
            'name' => 'auto_apply',
        ));

        if ($rule->getId()) {
            $fieldset->addField('rule_id', 'hidden', array(
                'name' => 'rule_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => $helper->__('Rule Name'),
            'title' => $helper->__('Rule Name'),
            'required' => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name' => 'description',
            'label' => $helper->__('Description'),
            'title' => $helper->__('Description'),
            'style' => 'height: 100px;',
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => $helper->__('Status'),
            'title'     => $helper->__('Status'),
            'name'      => 'is_active',
            'required' => true,
            'options'    => array(
                '1' => $helper->__('Active'),
                '0' => $helper->__('Inactive'),
            ),
        ));
        
        $fieldset->addField('strict', 'select', array(
            'label'     => $helper->__('Strict'),
            'title'     => $helper->__('Strict'),
            'name'      => 'strict',
            'required' => true,
            'options'    => array(
                '1' => $helper->__('Yes (remove existing category products)'),
                '0' => $helper->__('No (keep exisiting category products)'),
            )
        ));    
        
        $fieldset->addField('filter_new', 'select', array(
            'label'     => $helper->__('New Filter'),
            'title'     => $helper->__('New Filter'),
            'name'      => 'filter_new',
            'required' => true,
            'options'    => array(
                0 => $helper->__('No'),
                1 => $helper->__('Yes'),
            )
        )); 
        
        $fieldset->addField('filter_sale', 'select', array(
            'label'     => $helper->__('On Sale Filter'),
            'title'     => $helper->__('On Sale Filter'),
            'name'      => 'filter_sale',
            'required' => true,
            'options'    => array(
                0 => $helper->__('No'),
                1 => $helper->__('Yes'),
            )
        ));
        
        $fieldset->addField('limit', 'text', array(
            'name' => 'limit',
            'label' => $helper->__('Limit'),
            'title' => $helper->__('Limit'),
            'required' => false
        ));

        $form->setValues($rule->getData());

        if ($rule->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }
}