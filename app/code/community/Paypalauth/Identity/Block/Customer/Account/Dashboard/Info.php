<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @category    Paypalauth_Identity
 * @package     Paypalauth_Identity_Customer
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Dashboard Paypalauth_Identity Customer Info
 *
 * @category   Paypalauth_Identity
 * @package    Paypalauth_Identity_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Paypalauth_Identity_Block_Customer_Account_Dashboard_Info extends Mage_Customer_Block_Account_Dashboard_Info
{
    /**
     * Add paypalauth block to the customer dashboard
     *
     * @return string
     */
    protected function _toHtml()
    {
        $isExtensionEnabled = Mage::getStoreConfigFlag('customer/startup/paypalauth_enabled');

        $html = $this->getChildHtml('paypalauth_dashboard');
        if (!$isExtensionEnabled) {
            return parent::_toHtml();
        }
        $html .= parent::_toHtml();
        return $html;
    }

    /**
     * Check if this customer account linked with PayPal account
     *
     * @return bool
     */
    public function getPaypalCustomerEmail()
    {
        $customerId = $this->getCustomer()->getId();
        $paypalCustomerData =  Mage::getModel('paypalauth_identity/paypal_customer')->
                getPaypalCustomerDataByField('customer_id', $customerId);

        if ($paypalCustomerData['email']) {
            return $paypalCustomerData['email'];
        }
        return false;
    }

    /**
     * Return action url for unlinking (native magento customer entity and paypal customer entity)
     *
     * @return string
     */
    public function getUnlinkUrl()
    {
        return Mage::getUrl('login/openidconnect/unlink');
    }

    /**
     * Return action url for authorized magento customer
     *
     * @return string
     */
    public function getAuthLoginUrl()
    {
        return Mage::getUrl('login/openidconnect/login');
    }
}
