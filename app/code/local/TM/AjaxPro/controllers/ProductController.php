<?php

require_once 'Mage/Catalog/controllers/ProductController.php';

class TM_AjaxPro_ProductController extends Mage_Catalog_ProductController
{
    public function viewAction()
    {
        if (!$product = $this->_initProduct()) {

            if (!$this->getRequest()->isXmlHttpRequest()) {
                return $this->_redirectReferer();
            }

            $data = array(
                'status'  => false ,
                'message' => trim('Product not found', "\n\t ")
            );
            $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-Type', 'application/json')
                ->setBody(Mage::helper('core')->jsonEncode($data))
            ;
            return;
        }

        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->_redirectUrl($product->getProductUrl());
        }

        Mage::dispatchEvent('catalog_controller_product_view', array('product'=>$product));

        Mage::getSingleton('catalog/session')->setLastViewedProductId($product->getId());

        $helperClass = Mage::getConfig()->getHelperClassName('catalog/product_view');
        if (@class_exists($helperClass)) { //@ -warning magento autoloder disable
            Mage::helper('catalog/product_view')->initProductLayout($product, $this);
            $this->initLayoutMessages(array(
                'catalog/session', 'tag/session', 'checkout/session')
            );
        } else {
            Mage::getModel('catalog/design')->applyDesign($product, Mage_Catalog_Model_Design::APPLY_FOR_PRODUCT);

            $this->_initProductLayout($product);
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('tag/session');
            $this->_initLayoutMessages('checkout/session');
        }

        $content = $this->getLayout()->getBlock('product.info')->toHtml();

        // move local js variable to global scope
        $varscripts = array(
            'optionsPrice',
            'spConfig',
            'optionFileUpload',
            'optionTextCounter',
            'opConfig',
            'DateOption',
            'productAddToCartForm',
            'addTagFormJs',
            'bundle'
        );
        foreach ($varscripts as $varscript) {
            $content = str_replace('var ' . $varscript, $varscript, $content);
        }
	//validateOptionsCallback to global js scope
	$content = str_replace('function validateOptionsCallback(elmId, result){',
            'window.validateOptionsCallback = function (elmId, result){',
            $content
        );

	//fix configurable trabl (remove from form url bad suffix)
        $content = str_replace('?___SID=U', '', $content);

        // Replace current url with the url of the page where QuickShopping window was called
        // this is done for correct work of 'Continue Shopping', 'Add tag' buttons, etc.
        $currentEncodedUrl = substr(Mage::helper('core')->urlEncode(Mage::helper('core/url')->getCurrentUrl()), 0, -1);
        $refererEncodedUrl = $this->getRequest()->getParam(
            Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED,
            Mage::getBaseUrl()
        );
        $content = str_replace(
            $currentEncodedUrl,
            $refererEncodedUrl . '/',
            $content
        );

        $session = Mage::getModel('ajaxpro/session');
        $data = array(
            'ajaxpro-addcustomproduct-view' => trim($content, "\n\t "),
            'status'                        => $session->getStatus(),
            'messages'                      => $session->getMessages()
        );

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Content-Type', 'application/json')
            ->setBody(Mage::helper('core')->jsonEncode($data))
        ;
    }
}
