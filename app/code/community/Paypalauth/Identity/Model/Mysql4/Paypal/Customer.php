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
 * @category Paypalauth
 * @package Paypalauth_Identity
 * @copyright Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Paypal Customer Resource Model
 */
class Paypalauth_Identity_Model_Mysql4_Paypal_Customer extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Paypal customer resource model initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('paypalauth_identity/customer', 'id');
    }

    /**
     * Unlinks (native magento customer entity and paypal customer entity)
     *
     * @param $customerId
     * @return Paypalauth_Identity_Model_Mysql4_Paypal_Customer
     */
    public function unlinkAccount($customerId)
    {
        $this->_getWriteAdapter()->delete($this->getMainTable(), "customer_id={$customerId}");
        return $this;
    }

    /**
     * Check PayPal customer existing in the database
     *
     * @param string $customerId
     * @return bool
     */
    public function isPaypalCustomerExists($field, $value)
    {
        return (bool)$this->getPaypalCustomerDataByField($field, $value);
    }

    /**
     * Return payer_id(PayPal customer id) and customer_id
     *
     * @param $field
     * @param string $value
     * @return mixed
     */
    public function getPaypalCustomerDataByField($field, $value = '')
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), array('customer_id', 'payer_id', 'email'))
            ->where("$field=?", $value);
        $result = $this->_getReadAdapter()->fetchRow($select);

        return $result;
    }

}