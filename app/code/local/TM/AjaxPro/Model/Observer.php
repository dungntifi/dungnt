<?php

class TM_AjaxPro_Model_Observer {

    /**
     *
     * @var TM_AjaxPro_Model_Action
     */
    protected $_action;

    /**
     *
     * @return TM_AjaxPro_Model_Action
     */
    public function getAction()
    {
        if (!$this->_action instanceof TM_AjaxPro_Model_Action) {
            $this->_action = Mage::getModel('ajaxpro/action');
        }
        return $this->_action;
    }

    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function addBlockWrapper(Varien_Event_Observer $observer)
    {
        //core_block_abstract_to_html_after
        $block  = $observer->getBlock();
        $transport = $observer->getTransport();

        $blockName = $block->getNameInLayout();
        $allowedBlocks = array(
            'top.links', //wishlist_link
            'headerCart',
            'checkout.cart', //'checkout.cart.totals',
            'cart_sidebar',

            'customer.wishlist',
            'wishlist_sidebar',

            'catalog.compare.sidebar',
            'right.reports.product.compared'
        );
        
        $handles = $block->getRequest()->getParam('handles', array()); 
         
        if (in_array('suggestpage_index_index', $handles)
            || 'suggest' === $block->getRequest()->getModuleName()) {

            $allowedBlocks[] = 'content';
        }

        if (in_array($blockName, $allowedBlocks)) {
            $html = $transport->getHtml();
            if (empty($html)) {
                $html = '<span></span>'; // IE9 bugfix
            }
            $transport->setHtml(
                '<!-- ajaxpro_' .  $blockName . '_start -->' .
                  $html .
                '<!-- ajaxpro_' .  $blockName . '_end -->'
            );
        }
    }

    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function checkoutCartAddAction(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfig('ajax_pro/addToCart/enabled')) {
            return;
        }

        $blocks = array(
            'top.links',
            'headerCart',
            'checkout.cart', //'checkout.cart.totals',
            'cart_sidebar'
        );
        $handles = $observer->getControllerAction()
            ->getRequest()
            ->getParam('handles', array());
        if (in_array('suggestpage_index_index', $handles)) {
            $blocks[] = 'content';
        }

        /** @var $controllerAction Mage_Checkout_CartController */
        $controllerAction = $observer->getEvent()->getControllerAction();
        $action = $this->getAction()->setControllerAction($controllerAction);
        $action->getLayout()->setBlocks($blocks);
        $action->dispatch();
    }

//    /**
//     *
//     * @param Varien_Event_Observer $observer
//     * @return void
//     */
//    public function checkoutCartUpdatePostAction(Varien_Event_Observer $observer)
//    {
//        $this->getAction()->setSuccessMessage('Checkout cart was updated');
//        $this->checkoutCartAddAction($observer);
//    }

//    /**
//     *
//     * @param Varien_Event_Observer $observer
//     * @return void
//     */
//    public function checkoutCartUpdateItemOptionsAction(Varien_Event_Observer $observer)
//    {
//        $this->checkoutCartAddAction($observer);
//    }

    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function checkoutCartUpdateItemOptionsComplete(Varien_Event_Observer $observer)
    {
        Mage::getModel('checkout/session')->addNotice('Your will be redirected to shoping cart now');
    }

//    /**
//     *
//     * @param Varien_Event_Observer $observer
//     * @return void
//     */
//    public function checkoutCartDeleteAction(Varien_Event_Observer $observer)
//    {
//        $this->checkoutCartAddAction($observer);
//    }

    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function wishlistIndexAddAction(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfig('ajax_pro/addToWishlist/enabled')) {
            return;
        }
        Mage::unregister('shared_wishlist');
        Mage::unregister('wishlist');

        /** @var $controllerAction Mage_Wishlist_IndexController */
        $controllerAction = $observer->getEvent()->getControllerAction();

        $action = $this->getAction()->setControllerAction($controllerAction);
        $action->getLayout()->setBlocks(array(
            'top.links',

            'customer.wishlist',
            'wishlist_sidebar'
        ));
        $action->dispatch();
    }
//
//    /**
//     *
//     * @param Varien_Event_Observer $observer
//     * @return void
//     */
//    public function wishlistIndexRemoveAction(Varien_Event_Observer $observer)
//    {
//        $this->wishlistIndexAddAction($observer);
//    }
//
    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function wishlistIndexCartAction(Varien_Event_Observer $observer)
    {
        //$this->wishlistIndexAddAction($observer);
        if (!Mage::getStoreConfig('ajax_pro/addToWishlist/enabled')) {
            return;
        }
        /** @var $controllerAction Mage_Wishlist_IndexController */
        $controllerAction = $observer->getEvent()->getControllerAction();

        $action = $this->getAction()->setControllerAction($controllerAction);
        $action->getLayout()->setBlocks(array(
            'top.links',
            'customer.wishlist',
            'wishlist_sidebar',
            'headerCart',
            'cart_sidebar'
        ));
        $action->dispatch();
    }

    /**
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function catalogProductCompareAddAction(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfig('ajax_pro/addToCompare/enabled')) {
            return;
        }
        /** @var $controllerAction Mage_Catalog_Product_CompareController */
        $controllerAction = $observer->getEvent()->getControllerAction();

        $action = $this->getAction()->setControllerAction($controllerAction);
        $action->getLayout()->setBlocks(array(
            'catalog.compare.sidebar',
            'right.reports.product.compared'
        ));
        $action->dispatch();
    }

//    /**
//     *
//     * @param Varien_Event_Observer $observer
//     * @return void
//     */
//    public function preCatalogCategoryViewAction(Varien_Event_Observer $observer)
//    {
//        /** @var $controllerAction Mage_Catalog_Product_CompareController */
//        $controllerAction = $observer->getEvent()->getControllerAction();
////        Zend_Debug::dump($controllerAction->getRequest()->getParam('p', 1));
//    }
//
//    /**
//     *
//     * @param Varien_Event_Observer $observer
//     * @return void
//     */
//    public function postCatalogCategoryViewAction(Varien_Event_Observer $observer)
//    {
//        /** @var $controllerAction Mage_Catalog_Product_CompareController */
//        $controllerAction = $observer->getEvent()->getControllerAction();
//        $action = $this->getAction()->setControllerAction($controllerAction);
//        $action->setlayout(
//            $controllerAction->getLayout()
//        );
//
//        $action->getLayout()->setBlocks(array(
//            'product_list'
//        ));
//        $action->dispatch();
//    }
}
