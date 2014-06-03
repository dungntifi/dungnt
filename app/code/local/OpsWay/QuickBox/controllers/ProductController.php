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

class OpsWay_QuickBox_ProductController extends Mage_Core_Controller_Front_Action
{
    public function testAction()
    {
        //$this->loadLayout();
        //$this->renderLayout();
        echo 'testController';
    }

    public function viewAction(){

    }

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
}