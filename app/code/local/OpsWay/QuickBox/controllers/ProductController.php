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
 * @category    OpsWay
 * @package     OpsWay_QuickBox
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @generator   http://www.mgt-commerce.com/kickstarter/ Mgt Kickstarter
 */
require_once('app/code/core/Mage/Catalog/controllers/ProductController.php');

class OpsWay_QuickBox_ProductController extends Mage_Catalog_ProductController
{
    public function testAction()
    {
        //$this->loadLayout();
        //$this->renderLayout();
        echo 'testController';
    }

    /*public function viewAction(){
        //$this->loadLayout();
        //$this->renderLayout();
        //echo 'viewProduct';
                // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);
        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }
    }*/

    public function editAction(){
        $productId = $this->getRequest()->getParam('id');

        /** @var $viewHelper Mage_Catalog_Helper_Product_View */
        $viewHelper = Mage::helper('catalog/product_view');

        if ($item_id = $this->getRequest()->getParam('item')) {
            $item = Mage::getSingleton('checkout/session')->getQuote()->getItemById($item_id);
            $params = new Varien_Object(array('buy_request' => $item->getBuyRequest()));
        }

        $viewHelper->prepareAndRender($productId, $this, $params);
    }

    public function replaceAction()
    {
        /** @var $cart Mage_Checkout_Model_Cart */
        $cart = Mage::getSingleton('checkout/cart');
        $id = $this->getRequest()->getParam('replace_item_id');
        $params = $this->getRequest()->getParams();
        $params['id'] = $id;

        if (!isset($params['options'])) {
            $params['options'] = array();
        }
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $item = $cart->updateItem($id, new Varien_Object($params));
            if (is_string($item)) {
                Mage::throwException($item);
            }
            if ($item->getHasError()) {
                Mage::throwException($item->getMessage());
            }

            $cart->save();
            $this->_getSession()->setCartWasUpdated(true);
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}