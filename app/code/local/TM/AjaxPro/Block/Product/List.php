<?php
class TM_AjaxPro_Block_Product_List extends Mage_Catalog_Block_Product_List
{


    public function getCurrentUrl()
    {
        return Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true));
    }

    protected function _getRequest()
    {
        if (!$this->_request) {
            $this->_request = Mage::app()->getRequest();
        }
        return $this->_request;
    }

    /**
     * Retrieve url for add product to cart
     * Will return product view page URL if product has required options
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($product, $additional = array())
    {
        if (!Mage::getStoreConfig('ajax_pro/general/enabled')
            || !Mage::getStoreConfig('ajax_pro/general/showOptionsPopup')
            || TM_AjaxPro_Model_Action::isSearchBot()
            || (TM_AjaxPro_Model_Action::isMobile()
                    && Mage::getStoreConfig('ajax_pro/general/disabledOnMobileDevice'))
            ) {
            return parent::getAddToCartUrl($product, $additional);
        }

        if (defined('Mage_Core_Model_Url::FORM_KEY')) {
            $formKey = Mage::getSingleton('core/session')->getFormKey();
            if (!empty($formKey)) {
                $additional = array_merge(
                    $additional,
                    array(Mage_Core_Model_Url::FORM_KEY => $formKey)
                );
            }
        }
        
        $continueUrl    = Mage::helper('core')->urlEncode($this->getCurrentUrl());
        $urlParamName   = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;

        $routeParams = array(
            $urlParamName   => $continueUrl
        );

        $route = 'checkout/cart/add';
        if ($product->getTypeInstance(true)->hasRequiredOptions($product)
            || $product->getTypeInstance(true)->hasOptions($product)
            || 'grouped' === $product->getTypeId()) {
//
            $additional['options'] = 'cart';
            $route = 'ajaxpro/product/view';
            $routeParams['id'] = $product->getEntityId();
        } else {
            $routeParams['product'] = $product->getEntityId();
        }

        if (!empty($additional)) {
            $routeParams = array_merge($routeParams, $additional);
        }

        if ($product->hasUrlDataObject()) {
            $routeParams['_store'] = $product->getUrlDataObject()->getStoreId();
            $routeParams['_store_to_url'] = true;
        }

        if ($this->_getRequest()->getRouteName() == 'checkout'
            && $this->_getRequest()->getControllerName() == 'cart') {
            $routeParams['in_cart'] = 1;
        }

        return Mage::getUrl($route, $routeParams);
    }
}