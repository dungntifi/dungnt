<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Paypalauth
 * @package     Paypalauth_Identity
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Adminhtml Paypalauth Identity custom select in admin config
 *
 */
class Paypalauth_Identity_Block_Adminhtml_Form_Field_Secret extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Render select(enable/disable) login with PayPal
     * enable if web/secure url contains 'https'
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $paypalAuthSecret = Mage::getStoreConfig('customer/startup/paypalauth_secret');

        $form = $this->getForm();

        $label = $this->__('Secret');
		$element = new Varien_Data_Form_Element_Text();
        $element->setName('groups[startup][fields][paypalauth_secret][value]')
            ->setHtmlId('paypalauth_identity_secret_id')
            ->setId('customer_startup_paypalauth_secret')
            ->setForm($form)
            ->setLabel($label)
			->addClass('input-text')
			->addClass('required-entry')
            ->setValue($paypalAuthSecret);

        $html = parent::render($element);
        return $html;
    }

}
