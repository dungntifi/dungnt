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
 * Paypal Customer Model
 */

class Paypalauth_Identity_Model_Paypal_Customer extends Mage_Core_Model_Abstract
{

    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('paypalauth_identity/paypal_customer');
    }

    /**
     * Log in Magento
     *
     * @return Mage_Core_Model_Abstract
     */
    public function logInMagentoCustomerAccount($customerId)
    {
        $magentoCustomer = Mage::getModel('customer/customer')->load($customerId);
        $magentoCustomer->setConfirmation(null)->save();
        $magentoCustomer->setIsJustConfirmed(true);
        Mage::getModel('customer/session')->setCustomerAsLoggedIn($magentoCustomer);
        return $magentoCustomer;
    }

    /**
     * Unlink (native magento customer entity and paypal customer entity) from dashboard
     *
     * @return Paypalauth_Identity_Model_Paypal_Customer
     */
    public function unlinkAccount()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $this->_getResource()->unlinkAccount($customerId);
        return $this;
    }

    /**
     * Check paypalauth account existing in the database.
     * Method returns true if exists, false - otherwise.
     *
     * @param $field
     * @param $value
     * @return bool
     */
    public function isPaypalCustomerExists($field, $value)
    {
       return (bool) $this->_getResource()->isPaypalCustomerExists((string) $field, (string) $value);
    }

    /**
     * Return data array from paypalauth_customer table
     *
     * @param $field
     * @param $value
     * @return
     */
    public function getPaypalCustomerDataByField($field, $value)
    {
        $data = $this->_getResource()->getPaypalCustomerDataByField($field, $value);
        return $data;
    }

}