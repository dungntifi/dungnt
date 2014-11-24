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
class Paypalauth_Identity_Block_Adminhtml_Form_Field_Enable extends Mage_Adminhtml_Block_System_Config_Form_Field
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
        $paypalAuthEnabled = Mage::getStoreConfigFlag('customer/startup/paypalauth_enabled');

        $form = $this->getForm();
        $yesNoValues = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();

        $whiteListUrl = "https://developer.paypal.com/webapps/developer/docs/integration/direct/log-in-with-paypal/";
        $comment = $this->__("To enable Log in with PayPal please add your SSL secured website to the PayPal whitelist. It is easy to setup by visiting <a href='%s'>this url</a> and following the instructions. After you are whitelisted you can enable your customers to Log in with PayPal.", $whiteListUrl);

        $label = $this->__('Enable Log in with PayPal');
        
        $element = new Varien_Data_Form_Element_Select();
        $element->setName('groups[startup][fields][paypalauth_enabled][value]')
            ->setHtmlId('paypalauth_identity_enabled_id')
            ->setId('customer_startup_paypalauth_enabled')
            ->setForm($form)
            ->setValues($yesNoValues)
            ->setComment($comment)
            ->setLabel($label)
            ->setValue($paypalAuthEnabled);

        $html = parent::render($element);
        return $html;
    }

}
